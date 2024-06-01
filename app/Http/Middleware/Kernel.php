<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Kernel as HttpKernel;


class Kernel extends HttpKernel
{
    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */

    // Kernel.php
    protected $routeMiddleware = [
        // Middlewares
        'member' => \App\Http\Middleware\MemberMiddleware::class,
        'trainer' => \App\Http\Middleware\TrainerMiddleware::class,
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        'password-protected' => \App\Http\Middleware\PasswordProtected::class,
    ];

    protected $middlewareGroups = [
        'web' => [
            // \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            //  \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api-session' => [
            \Illuminate\Session\Middleware\StartSession::class,
        ],
    ];
}
