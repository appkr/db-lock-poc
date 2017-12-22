<?php

namespace Myshop\Domain\Model;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;
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
 * @property-read Collection|Review[] $reviews
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // RELATIONSHIPS

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // DOMAIN LOGIC

    public function isAdmin()
    {
        return $this->getKey() === 1;
    }

    // INTERFACE IMPLEMENTATION

    public function getJWTIdentifier()
    {
        // Will be used as the value of "sub(Subject)" key in JWT
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'user' => [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
            ]
        ];
    }
}
