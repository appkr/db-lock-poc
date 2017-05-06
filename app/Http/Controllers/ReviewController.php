<?php

namespace App\Http\Controllers;

use App\Http\Requests\Review\CreateReviewRequest;
use App\Http\Requests\Review\DeleteReviewRequest;
use App\Http\Requests\Review\ListReviewRequest;
use App\Http\Requests\Review\UpdateReviewRequest;
use Myshop\Domain\Model\Product;
use Myshop\Domain\Model\Review;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.basic.once', ['except' => 'index']);
    }

    public function index(ListReviewRequest $request)
    {
        return $request->all();
    }

    public function store(CreateReviewRequest $request, Product $product)
    {
        return $request->all();
    }

    public function update(
        UpdateReviewRequest $request, Product $product, Review $review
    ) {
        return $request->all();
    }

    public function destroy(
        DeleteReviewRequest $request, Product $product, Review $review
    ) {
        return $request->all();
    }
}
