<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\UnauthorizedException;
use Myshop\Common\Model\DomainRole;
use Myshop\Domain\Model\HasRoleAndPermission;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CheckRole
{
    private $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @param array|string[]|DomainRole[] $roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        /** @var HasRoleAndPermission $user */
        $user = $request->user();

        $this->validateParameters($roles);

        if (false === $user->hasAnyRole($roles)) {
            throw new UnauthorizedException('Forbidden');
        }

        return $next($request);
    }

    private function validateParameters(array $roles)
    {
        foreach ($roles as $roleName) {
            if (false === DomainRole::search($roleName)) {
                throw new BadRequestHttpException("Invalid middleware parameter: {$roleName}");
            }
        }
    }
}
