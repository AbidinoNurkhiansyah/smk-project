<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken() ?? $request->header('Authorization');
        
        // Remove "Bearer " prefix if present
        if ($token && strpos($token, 'Bearer ') === 0) {
            $token = substr($token, 7);
        }
        
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak ditemukan. Silakan login terlebih dahulu.'
            ], 401);
        }

        // Find user by API token
        $user = DB::table('users')
            ->where('api_token', $token)
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid atau telah kadaluarsa.'
            ], 401);
        }

        // Attach user to request
        $request->merge(['user' => $user]);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        return $next($request);
    }
}

