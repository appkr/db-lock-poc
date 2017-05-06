<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\DeleteProductRequest;
use App\Http\Requests\Product\ListProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use Myshop\Application\Service\ProductService;
use Myshop\Domain\Model\Product;

class ProductController extends Controller
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->middleware('auth.basic.once', ['except' => 'index']);
        $this->productService = $productService;
    }

    public function index(ListProductRequest $request)
    {
        // TODO: access repository directly
        return $request->all();
    }

    public function store(CreateProductRequest $request)
    {
        $product = $this->productService->makeProduct(
            $request->getProductDto()
        );

        $this->persist($product);

        return response()->json($product->fresh());
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product = $this->productService->modifyProduct(
            $product, $request->getProductDto()
        );

        $this->persist($product);

        return response()->json($product->fresh());
    }

    public function destroy(DeleteProductRequest $request, Product $product)
    {
        $this->productService->checkProductDeletePolicy($product);

        $this->remove($product);

        return response()->json([], 204);
    }
}
