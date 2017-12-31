<?php

namespace Myshop\Infrastructure\Eloquent;

use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Myshop\Common\Model\DomainRole;
use Myshop\Domain\Model\Role;
use Myshop\Domain\Repository\PermissionRepository;
use Myshop\Domain\Repository\RoleRepository;

class EloquentRoleRepository implements RoleRepository
{
    const CACHE_KEY = 'roles.all';
    const CACHE_TTL = 60 * 24;

    /** @var Collection $roleCollection */
    private $roleCollection;
    private $permissionRepository;

    public function __construct(
        PermissionRepository $permissionRepository,
        CacheRepository $cache
    ) {
        $this->permissionRepository = $permissionRepository;
        $this->roleCollection = $cache->remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Role::all();
        });
    }

    public function all(): Collection
    {
        return $this->roleCollection;
    }

    /**
     * {@inheritdoc}
     * @throws ModelNotFoundException
     */
    public function findById(int $id): Role
    {
        $role = $this->roleCollection->first(
            function (Role $role, int $key) use ($id) {
                return $id === $role->id;
            });

        if (is_null($role)) {
            throw new ModelNotFoundException;
        }

        return $role;
    }

    /**
     * {@inheritdoc}
     * @throws ModelNotFoundException
     */
    public function findByName(DomainRole $roleName): Role
    {
        $role = $this->roleCollection->first(
            function (Role $role, int $key) use ($roleName) {
                return $roleName == $role->name;
            });

        if (is_null($role)) {
            throw new ModelNotFoundException;
        }

        return $role;
    }

    public function save(Role $role): void
    {
        // NOTE. For cache clear logic
        // @see \Myshop\Infrastructure\ModelObserver\RoleObserver
        $role->push();
    }

    public function delete(Role $role): void
    {
        // TODO @appkr 맵핑 테이블 삭제를 어디서 할 것인가? Model Observer?
        // ON DELETE CASCADE 적용되어 있음.
        $role->delete();
    }
}