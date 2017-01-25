<?php
/**
 * Created by PhpStorm.
 * User: Nikhil
 * Date: 10/25/2015
 * Time: 3:07 PM
 */

namespace App\Services;


use App\User;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Error\InvalidRequest;
use Stripe\Refund;
use Stripe\Stripe;

class StripeClient
{

    protected $privateKey;
    protected $publicKey;

    public function __construct()
    {
        /*
         * Set Class Variables
         */
        $this->publicKey = env('STRIPE_PUBLIC_KEY');
        $this->privateKey = env('STRIPE_API_SECRET');

        Stripe::setApiKey($this->privateKey);

    }

    public function getPublicKey(){
        return $this->publicKey;
    }


    public function create_customer($user_id, $token){

        $user = User::findOrFail($user_id);

        // Add the Customer to Stripe
        $customer = Customer::create(array(
           'source' => $token,
            'email' => $user->email
        ));

        // Add the customer Id to the database
        $user->stripe_id = $customer->id;
        $user->save();

        return $customer;
    }

    public function delete_customer($user_id){
        $user = User::findOrFail($user_id);
        $customer = Customer::retrieve($user->stripe_id);
        $customer->delete();
    }

    public function add_payment_method($user_id, $token, $as_default = true){

        // Get the Stripe Customer Associated with the User
        $user = User::findOrFail($user_id);

        if(!$user->stripe_id){
            $customer = $this->create_customer($user_id, $token);
            return $this->get_default_payment_method_card_id($user_id);
        }

        try{
            $customer = Customer::retrieve($user->stripe_id);
        }catch (InvalidRequest $e){
            return $this->create_customer($user_id, $token);
        }

        // Create the new Card
        $card = $customer->sources->create(array(
            'source' => $token
        ));

        // Set the Card as the Customer's Default
        if($as_default){
            $this->set_default_payment_method($user_id, $card->id);
        }

        return $card;
    }

    public function delete_payment_method($user_id, $card_id){
        $user = User::find($user_id);
        $customer = Customer::retrieve($user->stripe_id);

        $result = $customer->sources->retrieve($card_id)->delete();
        return $result->deleted;
    }

    public function set_default_payment_method($user_id, $card_id){
        $user = User::find($user_id);
        $customer = Customer::retrieve($user->stripe_id);
        $customer->default_source = $card_id;
        $customer->save();
    }

    public function get_default_payment_method_card_id($user_id){
        $user = User::find($user_id);
        $customer = Customer::retrieve($user->stripe_id);
        return $customer->default_source;
    }

    public function payment_methods($user_id){
        $user = User::find($user_id);
        return Customer::retrieve($user->stripe_id)->sources->all(array(
            'object' => 'card'
        ));
    }

    public function has_payment_method($user_id){
        $user = User::find($user_id);
        if($user->stripe_id){
            return true;
        }else{
            return false;
        }
    }

    public function get_payment_method($user_id, $card_id){
        $user = User::find($user_id);
        $customer = Customer::retrieve($user->stripe_id);
        return $customer->sources->retrieve($card_id);
    }

    public function charge($user_id, $amount, $description = null){

        $user = User::find($user_id);

        $params = array(
            'amount' => $amount*100,
            'currency' => 'usd',
            'customer' => $user->stripe_id
        );

        if($description != null){
            $params['description'] = $description;
        }

        return Charge::create($params);
    }

    public function charge_guest($token, $amount, $description = null){

        $params = array(
            'amount' => $amount*100,
            'currency' => 'usd',
            'source' => $token
        );

        if($description != null){
            $params['description'] = $description;
        }

        return Charge::create($params);
    }

    public function refund_charge($charge_id){
        $refund = Refund::create(array(
            'charge' => $charge_id
        ));
        return $refund;
    }

}