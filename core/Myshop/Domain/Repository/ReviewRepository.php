<?php

namespace Myshop\Domain\Repository;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Myshop\Common\Dto\ReviewSearchParam;
use Myshop\Domain\Model\Product;
use Myshop\Domain\Model\Review;

interface ReviewRepository
{
    public function findById(int $id) : Review;
    public function findBySearchParam(Product $product, ReviewSearchParam $param) : LengthAwarePaginator;
    public function save(Review $review) : void;
    public function delete(Review $review) : void;
}