<?php

namespace Myshop\Application\Service;

use Myshop\Common\Dto\ProductDto;
use Myshop\Domain\Model\Product;

class ProductService
{
    public function makeProduct(ProductDto $dto) : Product
    {
        $product = new Product;

        $product->title = $dto->getTitle();
        $product->stock = $dto->getStock();
        $product->price = $dto->getPrice();
        $product->description = $dto->getDescription();

        return $product;
    }

    public function modifyProduct(Product $product, ProductDto $dto)
    {
        $product->title = $dto->getTitle() ?: $product->title;
        $product->stock = $dto->getStock() ?: $product->stock;
        $product->price = $dto->getPrice() ?: $product->price;
        $product->description = $dto->getDescription() ?: $product->description;

        return $product;
    }

    public function checkProductDeletePolicy(Product $product)
    {
        // Do something here
    }
}