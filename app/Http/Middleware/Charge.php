<?php

namespace App\Http\Middleware;

use App\Question;
use App\Services\Alert;
use App\Services\Notification;
use App\Services\StripeClient;
use App\User;
use Closure;
use Config;
use Stripe\Error\Card;


class Charge
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $question = Question::find($request->question);

        /*
         * Check if the solution is approved or return
         */
        if(!$question->hasApprovedSolution()){
            return back()->with('errors',['Solution is not approved']);
        }

        /*
         * If the user is not a paying user do not charge him
         */
        if($request->user() && !$request->user()->is_paying_user_for($question)){
            return $next($request);
        }

        /*
         * Get the Amount that the User Must be charged
         */
//        $tokens_required = $question->getApprovedSolution()->tokens_required;
        $tokens_required = config('constants.charge_per_question');


        /*
         * Check if the user has any tokens, if so use them
         */
        if($request->user() && $request->user()->tokens_remaining > 0){

            // Check if the user has enough tokens to cover the complete charge
            if($request->user()->tokens_remaining >= $tokens_required){
                $request->user()->tokens_remaining = $request->user()->tokens_remaining - $tokens_required;

                // The User has paid in full using tokens. Go next
                $request->session()->put('tokens_paid',$tokens_required);
                $request->user()->save();

                return $next($request);
            // If the user does not have sufficient tokens then use the tokens he has and charge the balance through braintree
            }else{
                $tokens_required = $tokens_required - $request->user()->tokens_remaining;
                $request->session()->put('tokens_paid',$request->user()->tokens_remaining);
                $request->user()->tokens_remaining = 0;
                $request->user()->save();
                // Continue to Charge the User through Stripe
            }


        }

        /*
         * If the user is subscribed charge the user through the subscription
         * Otherwise a Stripe Token Must accompany the request
         * Otherwise Fail
         */
        if($request->user() && $request->user()->subscribed()){
            $charge = $request->user()->charge_now($tokens_required);
            $request->session()->put('stripe_charge_id',$charge->id);
            $request->session()->put('stripe_charge_amount',$tokens_required);

        }elseif($request->has('token') && $request->input('token') != "" && $request->input('token') != null){
            $stripe_client = new StripeClient();
            try{
                $charge = $stripe_client->charge_guest($request->input('token'),$tokens_required);
            }
            catch(Card $e){
                $notification_client = new Notification();
                $notification_client->add_notification($e->getMessage(),'warning');

                $alert_client = new Alert();
                $alert_client->add_alert($e->getMessage(), 'warning');
            }

            $request->session()->put('stripe_charge_id',$charge->id);
            $request->session()->put('stripe_charge_amount',$tokens_required);

        }else{
            return back()->with('errors',['error1' => 'Please Subscribe or Pay using valid Credit card Details']);
        }

        /*
         * Log Charge
         */


        return $next($request);
    }
}
