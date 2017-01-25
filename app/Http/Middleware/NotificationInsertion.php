<?php

namespace App\Http\Middleware;

use App\Services\Notification;
use Closure;
use Session;

class NotificationInsertion
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
        $response = $next($request);

        $notifications = new Notification();

        if($request->ajax())
        {
            $array = json_decode($response->content(),true);
            $array['notifications'] = $notifications->getNotifications();
            $response->setContent(json_encode($array));
        }

        $notifications->resetNotifications();

        return $response;

    }
}
