<?php

namespace Myshop\Infrastructure\ModelObserver;

use Illuminate\Contracts\Cache\Repository;
use Myshop\Domain\Model\Role;

class RoleObserver
{
    const CACHE_KEY = 'roles.all';

    private $cache;

    public function __construct(Repository $cache)
    {
        $this->cache = $cache;
    }

    public function saved(Role $role)
    {
        $this->cache->forget(self::CACHE_KEY);
    }

    public function deleted(Role $role)
    {
        $role->permissions()->detach();
        $this->cache->forget(self::CACHE_KEY);
    }
}