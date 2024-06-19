<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Middleware\CheckToken;
use App\Models\User;

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
        // Ensure the request has a user (set by CheckToken middleware)
        if (!$request->user) {
            return response()->json(['message' => 'Unauthorized access'], 401);
        }

        // Check if user is an member
        if ($request->user->role !== 'member') {
            return response()->json(['message' => 'Unauthorized access'], 403);
        }

        // Proceed with request
        return $next($request);
    }
}

