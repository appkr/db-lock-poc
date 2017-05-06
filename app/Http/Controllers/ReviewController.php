<?php

namespace App\Http\Controllers;

use App\Http\Requests\Review\CreateReviewRequest;
use App\Http\Requests\Review\DeleteReviewRequest;
use App\Http\Requests\Review\ListReviewRequest;
use App\Http\Requests\Review\UpdateReviewRequest;
use Myshop\Application\Service\ReviewService;
use Myshop\Domain\Model\Product;
use Myshop\Domain\Model\Review;

class ReviewController extends Controller
{
    private $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->middleware('auth.basic.once', ['except' => 'index']);
        $this->reviewService = $reviewService;
    }

    public function index(ListReviewRequest $request)
    {
        // TODO: access repository directly
        return $request->all();
    }

    public function store(CreateReviewRequest $request, Product $product)
    {
        $review = $this->reviewService->makeReview(
            $product, $request->user(), $request->getReviewDto()
        );

        $this->persist($review);

        return response()->json($review->fresh());
    }

    public function update(
        UpdateReviewRequest $request, Product $product, Review $review
    ) {
        $review = $this->reviewService->modifyReview(
            $review, $request->getReviewDto()
        );

        $this->persist($review);

        return response()->json($review->fresh());
    }

    public function destroy(
        DeleteReviewRequest $request, Product $product, Review $review
    ) {
        $this->reviewService->checkReviewDeletePolicy($review);

        $this->remove($review);

        return response()->json([], 204);
    }
}
