<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\DeleteProductRequest;
use App\Http\Requests\Product\ListProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
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
        return $this->productRepository->findBySearchParam(
            $request->getProductSearchParam()
        );
    }

    public function store(CreateProductRequest $request)
    {
        $product = $this->productService->makeProduct(
            $request->getProductDto()
        );

        return response()->json($product);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product = $this->productService->modifyProduct(
            $product, $request->getProductDto()
        );

        return response()->json($product);
    }

    public function destroy(DeleteProductRequest $request, Product $product)
    {
        $this->productService->deleteProduct($product);

        return response()->json([], 204);
    }
}
