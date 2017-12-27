<?php

namespace Myshop\Domain\Model;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Myshop\Common\Model\DomainRole;
use Myshop\Infrastructure\ModelObserver\RoleObserver;

/**
 * Class Role
 * @package Myshop\Domain\Model
 *
 * @property int $id
 * @property DomainRole $name
 * @property string $guard
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection|Permission[] $permissions
 * @property-read Collection|User[] $users
 */
class Role extends Model
{
    // REGISTER MODEL OBSERVERS

    protected static function boot()
    {
        parent::boot();
        self::observe(RoleObserver::class);
    }

    // RELATIONSHIPS

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    // ACCESSOR & MUTATOR

    public function getNameAttribute(string $name)
    {
        return new DomainRole($name);
    }

    public function setNameAttribute(DomainRole $userRole)
    {
        $this->attributes['name'] = $userRole->getValue();
    }
}
