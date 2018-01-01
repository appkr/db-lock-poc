<?php

namespace Myshop\Domain\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *     definition="NewReviewRequest",
 *     type="object",
 *     required={ "title", "content" },
 *     @SWG\Property(
 *         property="title",
 *         type="string",
 *         description="제목",
 *         example="접착력이 약해요"
 *     ),
 *     @SWG\Property(
 *         property="content",
 *         type="string",
 *         description="본문",
 *         example="한 번 붙였다가 다시 붙이려고 하면, 접착이 안되요~"
 *    )
 * )
 * @SWG\Definition(
 *     definition="ReviewDto",
 *     type="object",
 *     required={ "id", "title", "content", "created_at", "updated_at", "author", "product" },
 *     allOf={
 *         @SWG\Schema(
 *             @SWG\Property(
 *                 property="author",
 *                 ref="#/definitions/UserDto"
 *             ),
 *             @SWG\Property(
 *                 property="product",
 *                 ref="#/definitions/ProductDto"
 *             ),
 *             @SWG\Property(
 *                 property="id",
 *                 type="integer",
 *                 format="int64",
 *                 description="ID",
 *                 example=987654321
 *             ),
 *             @SWG\Property(
 *                 property="version",
 *                 type="integer",
 *                 format="int32",
 *                 description="버전",
 *                 example=1
 *             )
 *         ),
 *         @SWG\Schema(ref="#/definitions/NewReviewRequest"),
 *         @SWG\Schema(ref="#/definitions/Timestamp")
 *     }
 * )
 *
 * @property int $id
 * @property int $product_id
 * @property int $user_id
 * @property string $title
 * @property string $content
 * @property int $version
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property User $author
 * @property Product $product
 */
class Review extends Model
{
    use SoftDeletes;

    protected $hidden = [
        'deleted_at',
    ];

    protected $casts = [
        // NOTE. SQLite 에서는 자동 캐스팅되지 않음.
        'user_id' => 'integer',
        'product_id' => 'integer',
        'version' => 'integer',
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

    public function isOwnedBy(User $user)
    {
        return $this->author->getKey() === $user->id;
    }
}
