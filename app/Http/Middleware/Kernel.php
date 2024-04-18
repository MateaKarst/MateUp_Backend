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
        // Other middleware entries...
        'member' => \App\Http\Middleware\MemberMiddleware::class,
    ];
}
