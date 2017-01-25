<?php

namespace App\Http\Middleware;
use App\User;
use Illuminate\Support\Facades\Session;
use Validator;
use Auth;
use Closure;
use App\Services\Notification;

class RegisterLoginSubscribe
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

        if($request->user() && $request->has('save_card_for_future') && $request->input('save_card_for_future') == 1){

            /*
             * Make Sure the Stripe Token is Provided and if so, Subscribe the User
             */
            if($request->has('token') && $request->input('token') != "" && $request->input('token') != null){
                $request->user()->add_payment_method($request->input('token'));
            }

        }

        return $next($request);
    }


}
