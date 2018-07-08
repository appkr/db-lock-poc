<?php

namespace App\Http\Middleware;

use App\Policies\ClientContextPolicy;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTGuard;

class ClientContextGuard
{
    private $guard;
    private $clientContextPolicy;

    public function __construct(JWTGuard $guard, ClientContextPolicy $clientContextPolicy)
    {
        $this->guard = $guard;
        $this->clientContextPolicy = $clientContextPolicy;
    }

    public function handle(Request $request, Closure $next)
    {
        if ($this->guard->check()) {
            $this->clientContextPolicy->check();
        }

        return $next($request);
    }
}
