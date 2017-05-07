<?php

namespace Myshop\Domain\Repository;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Myshop\Common\Dto\ProductSearchParam;
use Myshop\Domain\Model\Product;

interface ProductRepository
{
    public function findById(int $id) : Product;
    public function findByIdWithLock(int $id) : Product;
    public function findBySearchParam(ProductSearchParam $param) : LengthAwarePaginator;
    public function save(Product $product, int $version = null) : void;
    public function delete(Product $product) : void;
}