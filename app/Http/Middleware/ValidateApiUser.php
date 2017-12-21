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
        // 토큰 유효성을 검사합니다.
        $this->checkForToken($request);

        try {
            // 토큰으로부터 사용자를 식별합니다.
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

        // 토큰에서 "user" Custom Claim을 꺼냅니다.
        // @see \Myshop\Domain\Model\User::getJWTCustomClaims()
        // $customClaim = $this->guard->getPayload()->get('user');
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