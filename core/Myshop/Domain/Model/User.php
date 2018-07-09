<?php

namespace Myshop\Domain\Model;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;
use Myshop\Infrastructure\ModelObserver\UserObserver;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @SWG\Definition(
 *     definition="LoginRequest",
 *     type="object",
 *     required={ "email", "password" },
 *     @SWG\Property(
 *         property="email",
 *         type="string",
 *         description="사용자 이메일",
 *         example="user@example.com"
 *     ),
 *     @SWG\Property(
 *         property="password",
 *         type="string",
 *         description="사용자 비밀번호 (6 글자 이상)",
 *         example="secret"
 *     ),
 * ),
 * @SWG\Definition(
 *     definition="NewUserRequest",
 *     type="object",
 *     required={ "name", "email", "password" },
 *     allOf={
 *         @SWG\Schema(
 *             @SWG\Property(
 *                 property="name",
 *                 type="string",
 *                 description="사용자 이름",
 *                 example="User"
 *             )
 *         ),
 *         @SWG\Schema(ref="#/definitions/LoginRequest")
 *     }
 * ),
 * @SWG\Definition(
 *     definition="UserDto",
 *     type="object",
 *     required={ "id", "name", "email", "created_at", "updated_at" },
 *     allOf={
 *         @SWG\Schema(
 *             @SWG\Property(
 *                 property="id",
 *                 type="integer",
 *                 format="int64",
 *                 description="ID",
 *                 example="6523879503"
 *             )
 *         ),
 *         @SWG\Schema(ref="#/definitions/NewUserRequest"),
 *         @SWG\Schema(ref="#/definitions/Timestamp")
 *     }
 * )
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property array $allowed_ips
 * @property-read Collection|Review[] $reviews
 * @property-read Collection|Role[] $roles
 * @property-read Collection|Permission[] $permissions
 */
class User extends Authenticatable implements JWTSubject, HasRoleAndPermission
{
    use Notifiable;

    const DEFAULT_USER_ID = 1;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token', 'allowed_ips',
    ];

    protected $with = [
        'roles',
        'permissions',
    ];

    protected $casts = [
        'allowed_ips' => 'array',
    ];

    // REGISTER MODEL OBSERVERS

    protected static function boot()
    {
        parent::boot();
        self::observe(UserObserver::class);
    }

    // RELATIONSHIPS

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    // QUERY SCOPE
    // ACCESSOR & MUTATOR

    /**
     * @param string|null $allowedIps
     * @return array
     */
    public function getAllowedIpsAttribute($allowedIps)
    {
        if (null === $allowedIps) {
            return ['*'];
        }
        return (array) json_decode($allowedIps);
    }

    public function setAllowedIpsAttribute(array $allowedIps = [])
    {
        $this->attributes['allowed_ips'] = json_encode($allowedIps);
    }

    // DOMAIN LOGIC
    // INTERFACE IMPLEMENTATION

    /**
     * {@inheritdoc}
     */
    public function hasRole($role): bool
    {
        return $this->roles->contains(function (Role $item, int $key) use ($role) {
            $roleName = ($role instanceof Role) ? $role->name : $role;

            // NOTE.
            // DomainRole::FOO() === DomainRole::FOO() // false
            // DomainRole::FOO() == DomainRole::FOO() // true
            // DomainRole::FOO() == DomainRole::FOO // true
            return $item->name == $roleName;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function hasAnyRole($roles): bool
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function hasPermission($permission): bool
    {
        return $this->permissions->contains(function (Permission $item, int $key) use ($permission) {
            $permissionName = ($permission instanceof Permission)
                ? $permission->name : $permission;

            return $item->name == $permissionName;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function hasAnyPermission($permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getJWTIdentifier()
    {
        // Will be used as the value of "sub(Subject)" key in JWT
        return $this->getKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getJWTCustomClaims()
    {
        return [
            'user' => [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'roles' => $this->roles->implode('name', ','),
                'permissions' => $this->permissions->implode('name', ','),
            ]
        ];
    }

    // HELPERS

    public static function createDefaultUser(array $attributes = [])
    {
        $attributes = array_merge([
            'id' => static::DEFAULT_USER_ID,
            'name' => 'UNKNOWN',
            'email' => 'unknown@example.com',
        ], $attributes);

        return new static($attributes);
    }
}
