<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    // RELATIONSHIPS

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
