<?php

namespace Myshop\Infrastructure\Eloquent;

use DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Myshop\Common\Dto\ProductSearchParam;
use Myshop\Domain\Model\Product;
use Myshop\Domain\Model\Review;
use Myshop\Domain\Repository\ProductRepository;
use Myshop\Infrastructure\Exception\OptimisticLockingFailureException;

class EloquentProductRepository implements ProductRepository
{
    public function findById(int $id) : Product
    {
        return Product::findOrFail($id);
    }

    public function findByIdWithExclusiveLock(int $id): Product
    {
        return Product::lockForUpdate()->findOrFail($id);
    }

    public function findByIdWithSharedLock(int $id): Product
    {
        return Product::sharedLock()->findOrFail($id);
    }

    public function findBySearchParam(ProductSearchParam $param) : LengthAwarePaginator
    {
        $builder = Product::query();

        if ($keyword = $param->getKeyword()) {
            $builder->where(function (Builder $query) use ($keyword) {
                $query->where('title', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        if ($priceRange = $param->getPriceRange()) {
            $builder->whereBetween('price', [
                $priceRange->getBottom(),
                $priceRange->getTop(),
            ]);
        }

        return $builder->orderBy($param->getSortBy(), $param->getSortDirection())
            ->paginate($param->getSize(), ['*'], 'page', $param->getPage());
    }

    public function save(Product $product, int $version = null) : void
    {
        $this->checkVersionMatch($product, $version);
        $product->push();
    }

    public function delete(Product $product) : void
    {
        Review::whereIn('id', $product->reviews->pluck('id'))
            ->get()
            ->each(function (Review $review) {
                $review->delete();
            });

        $product->delete();
    }

    private function checkVersionMatch(Product $product, int $version = null)
    {
        if ($version && $product->fresh()->version !== $version) {
            throw new OptimisticLockingFailureException;
        }
    }
}