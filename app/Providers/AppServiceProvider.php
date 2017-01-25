<?php

namespace App\Providers;

use App\User;
use Illuminate\Support\ServiceProvider;
use App\ProblemEmail;
use Auth;
use Mail;
use Event;
use Log;
use Queue;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('user.credit_card', function($view){
            $view->with('default_payment_method_card_id',Auth::user()->get_default_payment_method_card_id());
        });


        // Filter Emails in the Bounce List
        Event::listen('mailer.sending',function(\Swift_Message $swiftype_message){

            // Get the Original Recipients of the Mail
            $recipients = $swiftype_message->getTo();
            Log::info('Original Recepients: '.json_encode($recipients));

            // Check if Any of the Email Addresses are in the Problem Emails Database
            $email_addresses = array_keys($recipients);
            $problem_emails = ProblemEmail::whereIn('email_address',$email_addresses)->get();
            Log::info('Problem Emails: '.json_encode($problem_emails));

            // Remove all the Problem Emails from the Recipients List
            foreach($problem_emails as $problem_email){
                unset($recipients[$problem_email->email_address]);
            }

            Log::info('Final Emails: '.json_encode($recipients));

            // Set the Recipients List Once Again
            // If the Number of recipients is less than 1, send a pretend mail
            // We do this, because sending a mail to 0 recipients throws an error
            if(count($recipients) > 0){
                $swiftype_message->setTo($recipients);
            }else{
                Mail::pretend();
            }

        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
