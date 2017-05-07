<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\DeleteProductRequest;
use App\Http\Requests\Product\ListProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Transformers\ProductTransformer;
use Myshop\Application\Service\ProductService;
use Myshop\Domain\Model\Product;
use Myshop\Domain\Repository\ProductRepository;

class ProductController extends Controller
{
    private $productService;
    private $productRepository;

    public function __construct(
        ProductService $productService, ProductRepository $productRepository
    ) {
        $this->middleware('auth.basic.once', ['except' => 'index']);
        $this->productService = $productService;
        $this->productRepository = $productRepository;
    }

    public function index(ListProductRequest $request)
    {
        $paginatedProductCollection = $this->productRepository->findBySearchParam(
            $request->getProductSearchParam()
        );

        return json()->withPagination(
            $paginatedProductCollection,
            new ProductTransformer
        );
    }

    public function store(CreateProductRequest $request)
    {
        $product = $this->productService->makeProduct(
            $request->getProductDto()
        );

        return json()->withItem($product, new ProductTransformer());
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product = $this->productService->modifyProduct(
            $product, $request->getProductDto()
        );

        return json()->withItem($product, new ProductTransformer());
    }

    public function destroy(DeleteProductRequest $request, Product $product)
    {
        $this->productService->deleteProduct($product);

        return json()->noContent();
    }
}
