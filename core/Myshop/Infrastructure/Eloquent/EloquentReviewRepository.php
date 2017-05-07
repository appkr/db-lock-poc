<?php

namespace Myshop\Infrastructure\Eloquent;

use DB;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Myshop\Common\Dto\ReviewSearchParam;
use Myshop\Domain\Model\Product;
use Myshop\Domain\Model\Review;
use Myshop\Domain\Repository\ReviewRepository;

class EloquentReviewRepository implements ReviewRepository
{

    public function findById(int $id, Product $product = null): Review
    {
        if ($product) {
            return Review::where('product_id', $product->id)->findOrFail($id);
        }

        return Review::findOrFail($id);
    }

    public function findByIdWithLock(int $id, Product $product = null): Review
    {
        if ($product) {
            return Review::where('product_id', $product->id)
                ->lockForUpdate()->findOrFail($id);
        }

        return Review::lockForUpdate()->findOrFail($id);
    }

    public function findBySearchParam(ReviewSearchParam $param, Product $product = null) : LengthAwarePaginator
    {
        $builder = Review::query();

        if ($product) {
            $builder->where('product_id', $product->id);
        }

        if ($userId = $param->getUserId()) {
            $builder->where('user_id', $userId);
        }

        if ($keyword = $param->getKeyword()) {
            $builder->where(function (Builder $query) use ($keyword) {
                $query->where('title', 'like', "%{$keyword}%")
                    ->orWhere('content', 'like', "%{$keyword}%");
            });
        }

        return $builder->orderBy($param->getSortBy(), $param->getSortDirection())
            ->paginate($param->getSize(), ['*'], 'page', $param->getPage());
    }

    public function save(Review $review) : void
    {
        DB::beginTransaction();

        try {
            $review->push();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(Review $review, Product $product = null) : void
    {
        $this->checkAssociationBetween($review, $product);

        DB::beginTransaction();

        try {
            $review->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function checkAssociationBetween(Review $review, Product $givenProduct = null)
    {
        if (is_null($givenProduct)) {
            return;
        }

        $associatedProduct = $review->product;

        if (! $associatedProduct) {
            throw new ModelNotFoundException;
        }

        if ($associatedProduct->id !== $givenProduct->id) {
            throw new ModelNotFoundException;
        }
    }
}