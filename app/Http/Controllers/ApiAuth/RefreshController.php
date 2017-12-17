<?php

namespace App\Http\Controllers\ApiAuth;

use App\Http\Controllers\Controller;
use Tymon\JWTAuth\JWTGuard;

class RefreshController extends Controller
{
    final public function __invoke(JWTGuard $guard)
    {
        $ttl = $guard->factory()->getTTL() * 60;

        return $this->respondWithToken($guard->refresh(), $ttl);
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
