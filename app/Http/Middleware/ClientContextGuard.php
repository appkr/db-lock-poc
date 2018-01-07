<?php

namespace App\Http\Middleware;

use App\Policies\ClientContextPolicy;
use Closure;
use Illuminate\Http\Request;
use Myshop\Common\Dto\AdditionalUserContextDto;
use Myshop\Domain\Model\User;
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
        // NOTE. ValidateApiUser 미들웨어보다 뒤에 위치해야 Null Pointer가 발생하지 않습니다.
        if ($this->guard->check()) {
            /** @var User $user */
            $user = $this->guard->user();
            $userContext = new AdditionalUserContextDto(
                $request->getHost(),
                $request->getClientIp(),
                $request->header('user-agent')
            );

            $this->clientContextPolicy->check($user, $userContext);
        }

        return $next($request);
    }
}
