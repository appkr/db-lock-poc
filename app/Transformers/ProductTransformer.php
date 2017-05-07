<?php

namespace App\Transformers;

use Myshop\Domain\Model\Product;
use Appkr\Api\TransformerAbstract;
use League\Fractal\ParamBag;

class ProductTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'reviews'
    ];

    protected $defaultIncludes = [];

    protected $visible = [];

    protected $hidden = ['reviews'];

    public function transform(Product $product)
    {
        return $this->buildPayload($product->toArray());
    }

    public function includeReviews(Product $product, ParamBag $paramBag = null)
    {
        $transformer = new ReviewTransformer($paramBag);

        $reviews = $product->reviews()
            ->limit($transformer->getLimit())
            ->offset($transformer->getOffset())
            ->orderBy($transformer->getSortKey(), $transformer->getSortDirection())
            ->get();

        return $this->collection($reviews, $transformer);
    }
}
