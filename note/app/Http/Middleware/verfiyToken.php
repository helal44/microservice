<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class verfiyToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
      
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if ($user) {
                return response()->json(['error' => $user], 401);
            }

        } catch (\Exception $e) {
            return response()->json(['error' =>'invalid'], 401);
        }

        return $next($request);
    }
}
