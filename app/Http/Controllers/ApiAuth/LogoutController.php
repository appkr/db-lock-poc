<?php

namespace App\Http\Controllers\ApiAuth;

use App\Http\Controllers\Controller;
use Tymon\JWTAuth\JWTGuard;

class LogoutController extends Controller
{
    final public function __invoke(JWTGuard $guard)
    {
        $guard->logout();

        // TODO @appkr Generalize response
        return response()->json(['message' => 'Successfully logged out']);
    }
}
