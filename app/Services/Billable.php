<?php

namespace App\Services;

trait Billable{

    public function add_payment_method($token){
        $stripe_client = new StripeClient();
        return $stripe_client->add_payment_method($this->id, $token);
    }

    public function get_default_payment_method_card_id(){
        $stripe_client = new StripeClient();
        return $stripe_client->get_default_payment_method_card_id($this->id);
    }

    public function update_default_payment_method($card_id){
        $stripe_client = new StripeClient();
        $stripe_client->set_default_payment_method($this->id, $card_id);
    }

    public function has_payment_method(){
        $stripe_client = new StripeClient();
        return $stripe_client->has_payment_method($this->id);
    }

    public function get_payment_method($card_id){
        $stripe_client = new StripeClient();
        return $stripe_client->get_payment_method($this->id, $card_id);
    }

    public function subscribed(){
        return $this->has_payment_method();
    }

    public function payment_methods(){
        $stripe_client = new StripeClient();
        return $stripe_client->payment_methods($this->id)->data;
    }

    public function charge($amount){
        $stripe_client = new StripeClient();
        return $stripe_client->charge($this->id, $amount);
    }

    public function charge_now($amount){
        return $this->charge($amount);
    }

    public function delete_payment_method($card_id){
        $stripe_client = new StripeClient();
        return $stripe_client->delete_payment_method($this->id, $card_id);
    }

}