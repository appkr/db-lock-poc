<?php

namespace Myshop\Infrastructure\Eloquent;

use DB;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Myshop\Common\Dto\ReviewSearchParam;
use Myshop\Domain\Model\Product;
use Myshop\Domain\Model\Review;
use Myshop\Domain\Repository\ReviewRepository;

class EloquentReviewRepository implements ReviewRepository
{

    public function findById(int $id): Review
    {
        Review::findOrFail($id);
    }

    public function findBySearchParam(Product $product, ReviewSearchParam $param) : LengthAwarePaginator
    {
        $builder = Review::query()->where('product_id', $product->id);

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

    public function delete(Review $review) : void
    {
        DB::beginTransaction();

        try {
            $review->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}