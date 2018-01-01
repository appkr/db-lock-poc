<?php

namespace Myshop\Infrastructure\Eloquent;

use DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Myshop\Common\Dto\ProductSearchParam;
use Myshop\Domain\Model\Product;
use Myshop\Domain\Model\Review;
use Myshop\Domain\Repository\ProductRepository;
use Myshop\Infrastructure\Exception\OptimisticLockingFailureException;

class EloquentProductRepository implements ProductRepository
{
    /**
     * {@inheritdoc}
     * @throws ModelNotFoundException
     */
    public function findById(int $id): Product
    {
        return Product::findOrFail($id);
    }

    /**
     * {@inheritdoc}
     * @throws ModelNotFoundException
     */
    public function findByIdWithExclusiveLock(int $id): Product
    {
        return Product::lockForUpdate()->findOrFail($id);
    }

    /**
     * {@inheritdoc}
     * @throws ModelNotFoundException
     */
    public function findByIdWithSharedLock(int $id): Product
    {
        return Product::sharedLock()->findOrFail($id);
    }

    public function findBySearchParam(ProductSearchParam $param): LengthAwarePaginator
    {
        $builder = Product::query();

        if (null !== $param->getKeyword()) {
            $builder->where(function (Builder $query) use ($param) {
                $keyword = $param->getKeyword();
                $query->where('title', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        $priceRange = $param->getPriceRange();

        if (null !== $priceRange->getBottom()) {
            $builder->where('price', '>=', $priceRange->getBottom());
        }
        if (null !== $priceRange->getTop()) {
            $builder->where('price', '<=', $priceRange->getTop());
        }

        return $builder->orderBy($param->getSortKey(), $param->getSortDirection())
            ->paginate($param->getSize(), ['*'], 'page', $param->getPage());
    }

    /**
     * {@inheritdoc}
     * @throws OptimisticLockingFailureException
     */
    public function save(Product $product, int $version = null)
    {
        $this->checkVersionMatch($product, $version);
        $product->push();
    }

    public function delete(Product $product)
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