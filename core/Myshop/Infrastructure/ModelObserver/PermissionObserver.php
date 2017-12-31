<?php

namespace Myshop\Infrastructure\ModelObserver;

use Illuminate\Contracts\Cache\Repository;
use Myshop\Domain\Model\Permission;

class PermissionObserver
{
    const CACHE_KEY = 'permissions.all';

    private $cache;

    public function __construct(Repository $cache)
    {
        $this->cache = $cache;
    }

    public function saved(Permission $permission)
    {
        $this->cache->forget(self::CACHE_KEY);
    }

    public function deleted(Permission $permission)
    {
        $this->cache->forget(self::CACHE_KEY);
    }
}