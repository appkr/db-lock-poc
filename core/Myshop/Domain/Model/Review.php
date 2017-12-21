<?php

namespace Myshop\Domain\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $product_id
 * @property int $user_id
 * @property string $title
 * @property string $content
 * @property int $version
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property User author
 * @property Product product
 */
class Review extends Model
{
    use SoftDeletes;

    protected $hidden = [
        'deleted_at',
    ];

    // RELATIONSHIPS

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // HELPERS

    public function isBelongsToUser(User $user)
    {
        return $this->getKey() === $user->id;
    }
}
