<?php

namespace App\Http\Controllers\ApiAuth;

use App\Http\Controllers\Controller;
use Tymon\JWTAuth\JWTGuard;

class MeController extends Controller
{
    final public function __invoke(JWTGuard $guard)
    {
        return response()->json($guard->user());
    }
}
