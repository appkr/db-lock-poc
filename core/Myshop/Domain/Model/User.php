<?php

namespace Myshop\Domain\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;

/**
 * @property int id
 * @property string name
 * @property string email
 * @property string password
 * @property Collection reviews
 */
class User extends Authenticatable
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
}
