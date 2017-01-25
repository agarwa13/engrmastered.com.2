<?php

namespace App\Http\Middleware;

use App\Services\Alert;
use Closure;

class RedirectIfNotEditor
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

        if(! $request->user()->isEditor()){

            $alert_client = new Alert();
            $alert_client->add_alert('You are not authorized to create and edit solutions yet. Please contact us at <a href="mailto:nikhil@engineeringmastered.com">nikhil@engineeringmastered.com</a> if you would like to solve questions and earn money on engrmastered.com','warning');

            return back();
        }

        return $next($request);
    }
}
