<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = $request->header('Authorization');

        if (!$authHeader) {
            return response()->json([
                'code' => 401,
                'message' => 'Authorization header missing.'
            ], 401);
        }

        try {
            // Call your Auth Service endpoint
            $response = Http::withHeaders([
                'Authorization' => $authHeader,
                'Accept' => 'application/json',
            ])->get('https://auth.transformbd.com/api/validateToken');

            if ($response->failed()) {
                return response()->json([
                    'code' => 401,
                    'message' => 'Unable to reach Auth Service or invalid response.'
                ], 401);
            }

            $result = $response->json();

            if (!isset($result['code']) || $result['code'] != 200) {
                return response()->json([
                    'code' => 401,
                    'message' => $result['message'] ?? 'Invalid or expired token.'
                ], 401);
            }

            // Inject account_id into request (so controllers can use it)
            $request->merge(['account_id' => $result['data']['account_id'] ?? null]);

            return $next($request);
        } catch (\Exception $e) {
            \Log::error('Token validation failed: ' . $e->getMessage());

            return response()->json([
                'code' => 500,
                'message' => 'Token validation error.'
            ], 500);
        }
    }
}
