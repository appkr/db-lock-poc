<?php

namespace Myshop\Infrastructure\Cache;

use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Myshop\Common\Model\DomainRole;
use Myshop\Domain\Model\Role;
use Myshop\Domain\Repository\RoleRepository;

class CachedRoleRepository implements RoleRepository
{
    const CACHE_KEY = 'roles.all';
    const CACHE_TTL = 60 * 24;

    private $baseRepository;
    private $cache;

    public function __construct(
        RoleRepository $baseRepository,
        CacheRepository $cache
    ) {
        $this->baseRepository = $baseRepository;
        $this->cache = $cache;
    }

    public function all(): Collection
    {
        return $this->cache->remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return $this->baseRepository->all();
        });
    }

    public function findById(int $id): Role
    {
        $role = $this->all()->first(
            function (Role $role, int $key) use ($id) {
                return $id === $role->id;
            });

        if (is_null($role)) {
            throw new ModelNotFoundException;
        }

        return $role;
    }

    public function findByName(DomainRole $roleName): Role
    {
        $role = $this->all()->first(
            function (Role $role, int $key) use ($roleName) {
                return $roleName == $role->name;
            });

        if (is_null($role)) {
            throw new ModelNotFoundException;
        }

        return $role;
    }

    public function save(Role $role)
    {
        // NOTE. For cache clear logic
        // @see \Myshop\Infrastructure\ModelObserver\RoleObserver
        $this->baseRepository->save($role);
    }

    public function delete(Role $role)
    {
        $this->baseRepository->delete($role);
    }
}