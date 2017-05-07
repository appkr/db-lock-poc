<?php

namespace App\Transformers;

use Myshop\Domain\Model\User;
use Appkr\Api\TransformerAbstract;
use League\Fractal\ParamBag;

class UserTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'reviews'
    ];

    protected $defaultIncludes = [];

    protected $visible = [];

    protected $hidden = [];

    public function transform(User $user)
    {
        return $this->buildPayload($user->toArray());
    }

    public function includeReviews(User $user, ParamBag $paramBag = null)
    {
        $transformer = new ReviewTransformer($paramBag);

        $reviews = $user->reviews()
            ->limit($transformer->getLimit())
            ->offset($transformer->getOffset())
            ->orderBy($transformer->getSortKey(), $transformer->getSortDirection())
            ->get();

        return $this->collection($reviews, $transformer);
    }
}
