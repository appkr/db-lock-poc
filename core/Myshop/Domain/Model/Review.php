<?php

namespace Myshop\Domain\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int id
 * @property int product_id
 * @property int user_id
 * @property string title
 * @property string content
 * @property mixed version
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
}
