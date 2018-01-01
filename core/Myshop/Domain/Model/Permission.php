<?php

namespace Myshop\Domain\Model;

use Illuminate\Database\Eloquent\Model;
use Myshop\Common\Model\DomainPermission;
use Myshop\Infrastructure\ModelObserver\PermissionObserver;

/**
 * Class Permission
 * @package Myshop\Domain\Model
 *
 * @property int $id
 * @property DomainPermission $name
 * @property string $guard
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection|Role[] $roles
 * @property-read Collection|User[] $users
 */
class Permission extends Model
{
    // REGISTER MODEL OBSERVERS

    protected static function boot()
    {
        parent::boot();
        self::observe(PermissionObserver::class);
    }

    // RELATIONSHIPS

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    // ACCESSOR & MUTATOR

    public function getNameAttribute(string $name)
    {
        return new DomainPermission($name);
    }

    public function setNameAttribute(DomainPermission $domainPermission)
    {
        $this->attributes['name'] = $domainPermission->getValue();
    }
}
