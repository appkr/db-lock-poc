<?php

namespace Myshop\Infrastructure\Eloquent;

use DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Myshop\Common\Dto\ReviewSearchParam;
use Myshop\Domain\Model\Product;
use Myshop\Domain\Model\Review;
use Myshop\Domain\Repository\ReviewRepository;
use Myshop\Infrastructure\Exception\OptimisticLockingFailureException;

class EloquentReviewRepository implements ReviewRepository
{

    public function findById(int $id, Product $product = null): Review
    {
        if ($product) {
            return Review::where('product_id', $product->id)->findOrFail($id);
        }

        return Review::findOrFail($id);
    }

    public function findByIdWithExclusiveLock(int $id, Product $product = null): Review
    {
        if ($product) {
            return Review::where('product_id', $product->id)
                ->lockForUpdate()->findOrFail($id);
        }

        return Review::lockForUpdate()->findOrFail($id);
    }

    public function findByIdWithSharedLock(int $id, Product $product = null): Review
    {
        if ($product) {
            return Review::where('product_id', $product->id)
                ->sharedLock()->findOrFail($id);
        }

        return Review::sharedLock()->findOrFail($id);
    }

    public function findBySearchParam(
        ReviewSearchParam $param,
        Product $product = null
    ) : LengthAwarePaginator {
        $builder = Review::query();

        if (null !== $product) {
            $builder->where('product_id', $product->id);
        }

        if (null !== $param->getUserId()) {
            $builder->where('user_id', $param->getUserId());
        }

        if (null !== $param->getKeyword()) {
            $builder->where(function (Builder $query) use ($param) {
                $keyword = $param->getKeyword();
                $query->where('title', 'like', "%{$keyword}%")
                    ->orWhere('content', 'like', "%{$keyword}%");
            });
        }

        return $builder->orderBy($param->getSortKey(), $param->getSortDirection())
            ->paginate($param->getSize(), ['*'], 'page', $param->getPage());
    }

    public function save(Review $review, int $version = null)
    {
        $this->checkVersionMatch($review, $version);
        $review->push();
    }

    public function delete(Review $review, Product $product = null)
    {
        $this->checkAssociationBetween($review, $product);
        $review->delete();
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

    private function checkVersionMatch(Review $review, int $version = null)
    {
        if ($version && $review->fresh()->version !== $version) {
            throw new OptimisticLockingFailureException;
        }
    }
}