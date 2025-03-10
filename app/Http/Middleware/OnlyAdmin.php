<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OnlyAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
            
        if (Auth::check() && Auth::user()->role !== 'user') {
            return $next($request);
        }

        
        return response()->json([
            'status' => 'insufficient_permissions',
            'message' => 'Access forbidden'
        ], 403);
    }
            
}
