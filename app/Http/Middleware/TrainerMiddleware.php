<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrainerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    // TrainerMiddleware.php
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated and has trainer role
        if (auth()->check() && auth()->user()->role === 'trainer') {
            // Proceed with request
            return $next($request);
        }

        // Return forbidden response
        abort(403, 'Unauthorized.');
    }
}
