<?php

namespace Myshop\Infrastructure\Eloquent;

use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Myshop\Common\Model\DomainPermission;
use Myshop\Domain\Model\Permission;
use Myshop\Domain\Repository\PermissionRepository;

class EloquentPermissionRepository implements PermissionRepository
{
    const CACHE_KEY = 'permissions.all';
    const CACHE_TTL = 60 * 24;

    /** @var Collection $permissionCollection */
    private $permissionCollection;

    public function __construct(CacheRepository $cache)
    {
        $this->permissionCollection = $cache->remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Permission::all();
        });
    }

    public function all(): Collection
    {
        return $this->permissionCollection;
    }

    /**
     * {@inheritdoc}
     * @throws ModelNotFoundException
     */
    public function findById(int $id): Permission
    {
        $permission = $this->permissionCollection->first(
            function (Permission $permission, int $key) use ($id) {
                return $id === $permission->id;
            });

        if (is_null($permission)) {
            throw new ModelNotFoundException;
        }

        return $permission;
    }

    /**
     * {@inheritdoc}
     * @throws ModelNotFoundException
     */
    public function findByName(DomainPermission $permissionName): Permission
    {
        $permission = $this->permissionCollection->first(
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
        $permission->push();
    }

    public function delete(Permission $permission): void
    {
        // TODO @appkr 맵핑 테이블 삭제를 어디서 할 것인가? Model Observer?
        // ON DELETE CASCADE 적용되어 있음.
        $permission->delete();
    }
}