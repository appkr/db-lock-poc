<?php

namespace App\Http\Middleware;

use Auth;

class AuthenticateOnceWithBasicAuth
{
    public function handle($request, $next)
    {
        return Auth::onceBasic() ?: $next($request);
    }
}