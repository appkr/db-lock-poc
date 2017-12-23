<?php

namespace App\Http\Controllers\ApiAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Tymon\JWTAuth\JWTGuard;

class LoginController extends Controller
{
    /**
     * @SWG\Post(
     *     path="/auth/login",
     *     operationId="login",
     *     tags={"Auth"},
     *     summary="로그인합니다.",
     *     consumes={"application/json", "application/x-www-form-urlencoded"},
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/LoginRequest"),
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
     * @param Request $request
     * @param JWTGuard $guard
     * @return \Illuminate\Http\JsonResponse
     */
    final public function __invoke(Request $request, JWTGuard $guard)
    {
        $credentials = $request->only('email', 'password');

        if ($token = $guard->attempt($credentials)) {
            $ttlInSec = $guard->factory()->getTTL() * 60;

            return $this->respondWithToken($token, $ttlInSec);
        }

        return response()->json([
            'code' => Response::HTTP_UNAUTHORIZED,
            'message' => '사용자 정보가 일치하지 않습니다.',
            'description' => 'Incorrect credentials',
        ], Response::HTTP_UNAUTHORIZED);
    }

    private function respondWithToken(string $token, int $ttlInSec)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $ttlInSec,
        ]);
    }
}
