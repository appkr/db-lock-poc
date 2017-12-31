<?php

namespace Myshop\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Myshop\Common\Model\DomainRole;
use Myshop\Domain\Model\Role;
use Myshop\Domain\Repository\RoleRepository;

class EloquentRoleRepository implements RoleRepository
{
    public function all(): Collection
    {
        return Role::all();
    }

    /**
     * {@inheritdoc}
     * @throws ModelNotFoundException
     */
    public function findById(int $id): Role
    {
        return Role::findOrFail($id);
    }

    /**
     * {@inheritdoc}
     * @throws ModelNotFoundException
     */
    public function findByName(DomainRole $roleName): Role
    {
        return Role::where('name', $roleName)->firstOrFail();
    }

    public function save(Role $role): void
    {
        $role->push();
    }

    public function delete(Role $role): void
    {
        // TODO @appkr 맵핑 테이블 삭제를 어디서 할 것인가? Model Observer?
        // ON DELETE CASCADE 적용되어 있음.
        $role->delete();
    }
}