<?php

namespace App\Http\Middleware;

use App\Services\Alert;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Request;


class Authenticate
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->guest()) {

            if($request->is('question/create')){
                $alert_client = new Alert();
                $alert_client->add_alert('You must login or create an account before you can ask a question','warning');
            }

            if($request->is('question/*/solution/create')){
                $alert_client = new Alert();
                $alert_client->add_alert('You must login or create an account before you can write solutions','warning');
            }

            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('auth/login');
            }
        }

        return $next($request);
    }
}
