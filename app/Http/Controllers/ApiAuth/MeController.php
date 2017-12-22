<?php

namespace App\Http\Controllers\ApiAuth;

use App\Http\Controllers\Controller;
use App\Transformers\UserTransformer;
use Appkr\Api\Http\Response;
use Tymon\JWTAuth\JWTGuard;

class MeController extends Controller
{
    /**
     * @SWG\Post(
     *     path="/auth/me",
     *     operationId="me",
     *     tags={"Auth"},
     *     summary="프로필 정보를 확인합니다.",
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
     *         response=201,
     *         description="성공",
     *         @SWG\Schema(ref="#/definitions/UserDto")
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         description="오류",
     *         @SWG\Schema(ref="#/definitions/ErrorDto")
     *     )
     * )
     *
     * @param JWTGuard $guard
     * @param Response $presenter
     * @return \Illuminate\Http\JsonResponse
     */
    final public function __invoke(JWTGuard $guard, Response $presenter)
    {
        return $presenter->withItem($guard->user(), new UserTransformer);
    }
}
