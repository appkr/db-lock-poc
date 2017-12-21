<?php

namespace Myshop\Domain\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Myshop\Common\Model\Money;

/**
 * @property int $id
 * @property string $title
 * @property int $stock
 * @property Money $price
 * @property string $description
 * @property int $version
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property-read Collection|Review[] reviews
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
