<?php

namespace Myshop\Application\Service;

use Myshop\Common\Dto\ProductDto;
use Myshop\Domain\Model\Product;
use Myshop\Domain\Repository\ProductRepository;

class ProductService
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function makeProduct(ProductDto $dto) : Product
    {
        $product = new Product;

        $product->title = $dto->getTitle();
        $product->stock = $dto->getStock();
        $product->price = $dto->getPrice();
        $product->description = $dto->getDescription();

        $this->productRepository->save($product);

        return $product->fresh();
    }

    public function modifyProduct(Product $product, ProductDto $dto)
    {
        // For HTTP PUT safety
        $product->title = $dto->getTitle() ?: $product->title;
        $product->stock = $dto->getStock() ?: $product->stock;
        $product->price = $dto->getPrice() ?: $product->price;
        $product->description = $dto->getDescription() ?: $product->description;

        // [비선점잠금]
        $retrievedVersion = $product->version;
        $product->version += 1;

        $this->productRepository->save($product, $retrievedVersion);

        return $product->fresh();
    }

    public function deleteProduct(Product $product)
    {
        $this->productRepository->delete($product);
    }
}