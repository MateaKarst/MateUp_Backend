<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MemberMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    
    // MemberMiddleware.php
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role === 'member') {
            return $next($request);
        }

        abort(403, 'Unauthorized.');
    }
}

