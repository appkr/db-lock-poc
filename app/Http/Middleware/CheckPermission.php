<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Myshop\Common\Model\DomainPermission;
use Myshop\Domain\Model\HasRoleAndPermission;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CheckPermission
{
    private $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @param array|string[]|DomainPermission[] $permissions
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        /** @var HasRoleAndPermission $user */
        $user = $request->user();

        $this->validateParameters($permissions);

        if (false === $user->hasAnyPermission($permissions)) {
            throw new UnauthorizedException('Forbidden');
        }

        return $next($request);
    }

    private function validateParameters(array $permissions)
    {
        foreach ($permissions as $permissionName) {
            if (false === DomainPermission::search($permissionName)) {
                throw new BadRequestHttpException("Invalid middleware parameter: {$permissionName}");
            }
        }
    }
}
