<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Middleware\CheckToken;
use App\Models\User;

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
        // Ensure the request has a user (set by CheckToken middleware)
        if (!$request->user) {
            return response()->json(['message' => 'Unauthorized access'], 401);
        }

        // Check if user is an trainer
        if ($request->user->role !== 'trainer') {
            return response()->json(['message' => 'Unauthorized access'], 403);
        }

        // Proceed with request
        return $next($request);
    }
}
