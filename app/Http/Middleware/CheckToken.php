<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {

        // Check if the token is in the request or from sanctum
        if($request->header('Authorization')) {
            $token = $request->header('Authorization');
        } else {
            $user = Auth::guard('sanctum')->user();

            if (!$user) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        
        }

        // if no token
        if (!$token) {
            return response()->json(['message' => 'Unauthorized access'], 401);
        }

        // if token check if it exists in the database
        $user = User::where('user_token', $token)->first();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized access'], 401);
        }

        // Allow request to proceed
        $request->user = $user;

        return $next($request);
    }
}
