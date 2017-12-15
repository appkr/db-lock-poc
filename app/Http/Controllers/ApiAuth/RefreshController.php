<?php

namespace App\Http\Controllers\ApiAuth;

use App\Http\Controllers\Controller;
use Tymon\JWTAuth\JWTGuard;

class RefreshController extends Controller
{
    /**
     * @SWG\Post(
     *     path="/auth/refresh",
     *     operationId="refreshToken",
     *     tags={"Auth"},
     *     summary="로그인합니다.",
     *     consumes={"application/json", "application/x-www-form-urlencoded"},
     *     @SWG\Parameter(
     *         name="Authorization",
     *         in="header",
     *         required=true,
     *         type="string",
     *         description="액세스 토큰",
     *         default="Bearer "
     *     ),
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="성공",
     *         @SWG\Schema(ref="#/definitions/AccessToken")
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         description="오류",
     *         @SWG\Schema(ref="#/definitions/ErrorDto")
     *     )
     * )
     *
     * @param JWTGuard $guard
     * @return \Illuminate\Http\JsonResponse
     */
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
