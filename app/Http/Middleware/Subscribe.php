<?php

namespace App\Http\Middleware;

use Closure;

class Subscribe
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
        if(! $request->user()->subscribed()){
            $request->flash();
            return back()->with('errors',[
                'error1' => 'Hello there, Please Save A Payment Method First. <a class="subscribeButton">Click Here to Save a Payment Method Securely with Stripe</a>'
            ]);
        }

        return $next($request);
    }
}
