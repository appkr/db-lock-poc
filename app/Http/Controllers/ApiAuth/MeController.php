<?php

namespace App\Http\Controllers\ApiAuth;

use App\Http\Controllers\Controller;
use App\Transformers\UserTransformer;
use Appkr\Api\Http\Response;
use Tymon\JWTAuth\JWTGuard;

class MeController extends Controller
{
    final public function __invoke(JWTGuard $guard, Response $presenter)
    {
        return $presenter->withItem($guard->user(), new UserTransformer);
    }
}
