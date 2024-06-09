<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            // Middlewares
            'member' => \App\Http\Middleware\MemberMiddleware::class,
            'trainer' => \App\Http\Middleware\TrainerMiddleware::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'customAuth' => \App\Http\Middleware\AuthMiddleware::class,
            'api-session' => \Illuminate\Session\Middleware\StartSession::class,
            'checkToken' => \App\Http\Middleware\CheckToken::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
