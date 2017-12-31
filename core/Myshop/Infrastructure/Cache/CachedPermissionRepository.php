<?php

namespace Myshop\Infrastructure\Cache;

use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Myshop\Common\Model\DomainPermission;
use Myshop\Domain\Model\Permission;
use Myshop\Domain\Repository\PermissionRepository;

class CachedPermissionRepository implements PermissionRepository
{
    const CACHE_KEY = 'permissions.all';
    const CACHE_TTL = 60 * 24;

    private $baseRepository;
    private $cache;

    public function __construct(
        PermissionRepository $baseRepository,
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

    public function findById(int $id): Permission
    {
        $permission = $this->all()->first(
            function (Permission $permission, int $key) use ($id) {
                return $id === $permission->id;
            });

        if (is_null($permission)) {
            throw new ModelNotFoundException;
        }

        return $permission;
    }

    public function findByName(DomainPermission $permissionName): Permission
    {
        $permission = $this->all()->first(
            function (Permission $permission, int $key) use ($permissionName) {
                return $permissionName == $permission->name;
            });

        if (is_null($permission)) {
            throw new ModelNotFoundException;
        }

        return $permission;
    }

    public function save(Permission $permission): void
    {
        // NOTE. For cache clear logic
        // @see \Myshop\Infrastructure\ModelObserver\PermissionObserver
        $this->baseRepository->save($permission);
    }

    public function delete(Permission $permission): void
    {
        $this->baseRepository->save($permission);
    }
}