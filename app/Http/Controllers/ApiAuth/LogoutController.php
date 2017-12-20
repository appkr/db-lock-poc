<?php

namespace App\Http\Controllers\ApiAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Tymon\JWTAuth\JWTGuard;

class LogoutController extends Controller
{
    final public function __invoke(JWTGuard $guard)
    {
        $guard->logout();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
