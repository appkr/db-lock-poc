<?php

namespace App\Http\Controllers;

use App\Http\Requests\Review\CreateReviewRequest;
use App\Http\Requests\Review\DeleteReviewRequest;
use App\Http\Requests\Review\ListReviewRequest;
use App\Http\Requests\Review\UpdateReviewRequest;
use Myshop\Application\Service\ReviewService;
use Myshop\Domain\Model\Product;
use Myshop\Domain\Model\Review;
use Myshop\Domain\Repository\ReviewRepository;

class ReviewController extends Controller
{
    private $reviewService;
    private $reviewRepository;

    public function __construct(
        ReviewService $reviewService, ReviewRepository $reviewRepository
    ) {
        $this->middleware('auth.basic.once', ['except' => 'index']);
        $this->reviewService = $reviewService;
        $this->reviewRepository = $reviewRepository;
    }

    public function index(ListReviewRequest $request, Product $product)
    {
        return $this->reviewRepository->findBySearchParam(
            $product, $request->getReviewSearchParam()
        );
    }

    public function store(CreateReviewRequest $request, Product $product)
    {
        $review = $this->reviewService->makeReview(
            $product, $request->user(), $request->getReviewDto()
        );

        return response()->json($review);
    }

    public function update(
        UpdateReviewRequest $request, Product $product, Review $review
    ) {
        $review = $this->reviewService->modifyReview(
            $review, $request->getReviewDto()
        );

        return response()->json($review);
    }

    public function destroy(
        DeleteReviewRequest $request, Product $product, Review $review
    ) {
        $this->reviewService->deleteReview($review);

        return response()->json([], 204);
    }
}
