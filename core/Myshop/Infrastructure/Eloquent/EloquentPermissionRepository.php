<?php

namespace Myshop\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Myshop\Common\Model\DomainPermission;
use Myshop\Domain\Model\Permission;
use Myshop\Domain\Repository\PermissionRepository;

class EloquentPermissionRepository implements PermissionRepository
{
    public function all(): Collection
    {
        return Permission::all();
    }

    /**
     * {@inheritdoc}
     * @throws ModelNotFoundException
     */
    public function findById(int $id): Permission
    {
        return Permission::findOrFail($id);
    }

    /**
     * {@inheritdoc}
     * @throws ModelNotFoundException
     */
    public function findByName(DomainPermission $permissionName): Permission
    {
        return Permission::where('name', $permissionName)->firstOrFail();
    }

    public function save(Permission $permission): void
    {
        $permission->push();
    }

    public function delete(Permission $permission): void
    {
        // TODO @appkr 맵핑 테이블 삭제를 어디서 할 것인가? Model Observer?
        // ON DELETE CASCADE 적용되어 있음.
        $permission->delete();
    }
}