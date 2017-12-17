<?php

namespace App\Http\Controllers\ApiAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Tymon\JWTAuth\JWTGuard;

class LoginController extends Controller
{
    final public function __invoke(Request $request, JWTGuard $guard)
    {
        $credentials = $request->only('email', 'password');

        if ($token = $guard->attempt($credentials)) {
            $ttl = $guard->factory()->getTTL() * 60;

            return $this->respondWithToken($token, $ttl);
        }

        // TODO @appkr Generalize response
        return response()->json([
            'error' => 'Unauthorized',
        ], Response::HTTP_UNAUTHORIZED);
    }

    private function respondWithToken(string $token, int $ttl)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $ttl,
        ]);
    }
}
