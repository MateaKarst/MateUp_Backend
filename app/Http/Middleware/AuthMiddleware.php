<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    // Use laravel sanctum
    public function handle(Request $request, Closure $next)
    {
        try {
            // Check if token is present and valid
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'Unauthorized', 'message' => 'User not found'], 401);
            }

            // Allow request to proceed
            return $next($request);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Unauthorized', 'message' => $e->getMessage()], 401);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error', 'message' => $e->getMessage()], 500);
        }
    }
}
