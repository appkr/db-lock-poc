<?php

namespace Myshop\Infrastructure\Eloquent;

use DB;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Myshop\Common\Dto\ProductSearchParam;
use Myshop\Domain\Model\Product;
use Myshop\Domain\Model\Review;
use Myshop\Domain\Repository\ProductRepository;

class EloquentProductRepository implements ProductRepository
{
    public function findById(int $id) : Product
    {
        return Product::findOrFail($id);
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

        $priceFrom = $param->getPriceFrom();
        $priceTo = $param->getPriceTo();

        if ($priceFrom && $priceTo) {
            $builder->whereBetween('price', [$priceFrom, $priceTo]);
        }

        if ($priceFrom && ! $priceTo) {
            $builder->where('price', '>=', $priceFrom);
        }

        if (! $priceFrom && $priceTo) {
            $builder->where('price', '<=', $priceTo);
        }

        return $builder->orderBy($param->getSortBy(), $param->getSortDirection())
            ->paginate($param->getSize(), ['*'], 'page', $param->getPage());
    }

    public function save(Product $product) : void
    {
        DB::beginTransaction();

        try {
            $product->push();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(Product $product) : void
    {
        $reviewIds = $product->reviews->pluck('id')->toArray();

        DB::beginTransaction();

        try {
            Review::destroy($reviewIds);
            $product->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}