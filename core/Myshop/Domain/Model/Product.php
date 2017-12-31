<?php

namespace Myshop\Domain\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Myshop\Common\Model\Money;

/**
 * @SWG\Definition(
 *     definition="NewProductRequest",
 *     type="object",
 *     required={"title", "stock", "price", "description"},
 *     @SWG\Property(
 *         property="title",
 *         type="string",
 *         description="상품명",
 *         example="[특가] 반짝반짝 빛나는 에폭시 스티커"
 *    ),
 *     @SWG\Property(
 *         property="stock",
 *         type="integer",
 *         format="int32",
 *         description="재고수량",
 *         example=100
 *    ),
 *     @SWG\Property(
 *         property="price",
 *         type="integer",
 *         format="int64",
 *         description="가격",
 *         example=1600
 *    ),
 *     @SWG\Property(
 *         property="description",
 *         type="string",
 *         description="상품 설명",
 *         example="라이언 캐릭터를 주제로 한 투명 에폭시 스티커.."
 *    )
 * )
 * @SWG\Definition(
 *     definition="ProductDto",
 *     type="object",
 *     required={ "id", "title", "stock", "price", "description", "created_at", "updated_at" },
 *     allOf={
 *         @SWG\Schema(
 *             @SWG\Property(
 *                 property="id",
 *                 type="integer",
 *                 format="int64",
 *                 description="ID",
 *                 example=245134578
 *             ),
 *             @SWG\Property(
 *                 property="version",
 *                 type="integer",
 *                 format="int32",
 *                 description="버전",
 *                 example=1
 *             )
 *         ),
 *         @SWG\Schema(ref="#/definitions/NewProductRequest"),
 *         @SWG\Schema(ref="#/definitions/Timestamp")
 *     }
 * )
 *
 * @property int $id
 * @property string $title
 * @property int $stock
 * @property Money $price
 * @property string $description
 * @property int $version
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property-read Collection|Review[] $reviews
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

    protected $casts = [
        // NOTE. SQLite에서는 자동 캐스팅되지 않음.
        'stock' => 'integer',
        'price' => 'integer',
        'version' => 'integer',
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
