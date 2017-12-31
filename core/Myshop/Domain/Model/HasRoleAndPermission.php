<?php

namespace Myshop\Domain\Model;

use Illuminate\Support\Collection;
use Myshop\Common\Model\DomainPermission;
use Myshop\Common\Model\DomainRole;

interface HasRoleAndPermission
{
    /**
     * @param Role|DomainRole|string $role;
     * @return bool
     */
    public function hasRole($role): bool;

    /**
     * @param array|DomainRole[]|Role[]|Collection $roles
     * @return bool
     */
    public function hasAnyRole($roles): bool;

    /**
     * @param Permission|DomainPermission|string $permission
     * @return bool
     */
    public function hasPermission($permission): bool;

    /**
     * @param array|DomainPermission[]|Permission[]|Collection $permissions
     * @return bool
     */
    public function hasAnyPermission($permissions): bool;
}