<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'status' => 'invalid_token',
                'message' => 'Invalid or expired token',
            ], 401);
        }
        $accessToken = PersonalAccessToken::findToken($token);
        $user = $accessToken->tokenable;

        if (!$user) {
            return response()->json([
                'status' => 'invalid_token',
                'message' => 'Invalid or expired token',
            ], 401);
        }
        Auth::login($user);
        return $next($request);
    }
    
}
