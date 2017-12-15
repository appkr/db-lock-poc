<?php

namespace App\Http\Controllers\ApiAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Tymon\JWTAuth\JWTGuard;

class LogoutController extends Controller
{
    /**
     * @SWG\Post(
     *     path="/auth/logout",
     *     operationId="logout",
     *     tags={"Auth"},
     *     summary="로그아웃합니다.",
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
     *         response=204,
     *         description="성공"
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
        $guard->logout();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
