<?php

namespace Myshop\Domain\Repository;

use Illuminate\Support\Collection;
use Myshop\Common\Model\DomainPermission;
use Myshop\Domain\Model\Permission;

interface PermissionRepository
{
    public function all(): Collection;
    public function findById(int $id): Permission;
    public function findByName(DomainPermission $permissionName): Permission;
    public function save(Permission $permission): void;
    public function delete(Permission $permission): void;
}