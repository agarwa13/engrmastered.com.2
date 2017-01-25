<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \App\Http\Middleware\NotificationInsertion::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'editor' => \App\Http\Middleware\RedirectIfNotEditor::class,
        'admin' => \App\Http\Middleware\RedirectIfNotAdmin::class,
        'update_usage_table' => \App\Http\Middleware\UpdateUsageTable::class,
        'subscribed' => \App\Http\Middleware\Subscribe::class,
        'charge' => \App\Http\Middleware\Charge::class,
        'pay' => \App\Http\Middleware\Pay::class,
        'question_inputs' => \App\Http\Middleware\BackIfQuestionNotFilledOut::class,
        'register_login_subscribe' => \App\Http\Middleware\RegisterLoginSubscribe::class,
        'CORS' => \App\Http\Middleware\CORS::class,
    ];
}
