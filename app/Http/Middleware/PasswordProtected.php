<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PasswordProtected
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Check if the password in the request matches the one in the .env file
        $password = $request->header('Authorization');

        if ($password !== env('API_ACCESS_PASSWORD')) {
            return response()->json(['message' => 'Unauthorized access'], 401);
        }

        return $next($request);
    }
}
