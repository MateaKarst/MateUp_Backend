<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    
    // AdminMiddleware.php
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated and has admin role
        if (auth()->check() && auth()->user()->role === 'admin') {
            // Proceed with request
            return $next($request);
        }

        // Return forbidden response
        abort(403, 'Unauthorized.');
    }
}
