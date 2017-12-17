<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;

class ValidateApiUser
{
    private $guard;

    public function __construct(JWTAuth $guard)
    {
        $this->guard = $guard;
    }

    final public function handle($request, Closure $next)
    {
        $this->authenticate($request);

        return $next($request);
    }

    private function authenticate(Request $request)
    {
        $this->checkForToken($request);

        try {
            if (! $this->guard->parseToken()->authenticate()) {
                throw new UnauthorizedHttpException(
                    'jwt-auth',
                    'User not found'
                );
            }
        } catch (JWTException $e) {
            throw new UnauthorizedHttpException(
                'jwt-auth',
                $e->getMessage(),
                $e,
                $e->getCode()
            );
        }
    }

    private function checkForToken(Request $request)
    {
        if (! $this->guard->parser()->setRequest($request)->hasToken()) {
            throw new UnauthorizedHttpException(
                'jwt-auth',
                'Token not provided'
            );
        }
    }
}