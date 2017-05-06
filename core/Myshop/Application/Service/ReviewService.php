<?php

namespace Myshop\Application\Service;

use Illuminate\Contracts\Auth\Authenticatable;
use Myshop\Common\Dto\ReviewDto;
use Myshop\Domain\Model\Product;
use Myshop\Domain\Model\Review;

class ReviewService
{
    public function makeReview(Product $product, Authenticatable $user, ReviewDto $dto) : Review
    {
        $review = new Review;

        $review->title = $dto->getTitle();
        $review->content = $dto->getContent();
        $review->author()->associate($user);
        $review->product()->associate($product);

        return $review;
    }

    public function modifyReview(Review $review, ReviewDto $dto) : Review
    {
        $review->title = $dto->getTitle() ?: $review->title;
        $review->content = $dto->getContent() ?: $review->content;

        return $review;
    }

    public function checkReviewDeletePolicy(Review $review)
    {
        // Do something here
    }
}