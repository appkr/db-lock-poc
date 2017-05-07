<?php

namespace Myshop\Domain\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Myshop\Common\Model\Money;

/**
 * @property int id
 * @property string title
 * @property int stock
 * @property Money price
 * @property string description
 * @property Collection reviews
 */
class Product extends Model
{
    use SoftDeletes;

    protected $with = [
        'reviews',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    // RELATIONSHIPS

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // ACCESSOR & MUTATORS

    public function getPriceAttribute(int $price)
    {
        return new Money($price);
    }

    public function setPriceAttribute(Money $price)
    {
        $this->attributes['price'] = $price->getAmount();
    }
}
