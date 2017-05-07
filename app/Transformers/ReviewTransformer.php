<?php

namespace App\Transformers;

use Myshop\Domain\Model\Review;
use Appkr\Api\TransformerAbstract;
use League\Fractal\ParamBag;

class ReviewTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'product',
        'author',
    ];

    protected $defaultIncludes = [
        'author',
    ];

    protected $visible = [];

    protected $hidden = [
        'user_id',
    ];

    public function transform(Review $review)
    {
        return $this->buildPayload($review->toArray());
    }

    public function includeProduct(Review $review, ParamBag $paramBag = null)
    {
        return $this->item(
            $review->product,
            new ProductTransformer($paramBag)
        );
    }

    public function includeAuthor(Review $review, ParamBag $paramBag = null)
    {
        return $this->item(
            $review->author,
            new UserTransformer($paramBag)
        );
    }
}
