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

    public function update(UpdateProductRequest $request, int $productId)
    {
        // [선점잠금] 레코드를 조회하고 잠급니다.
        $product = $this->productRepository->findByIdWithLock($productId);

        // [선점잠금] PoC를 위해 강제로 잠금을 연장합니다.
        // 선점한 프로세스 A가 끝나고 DB 잠금이 풀리면, 다음 프로세스 B를 처리합니다.
        sleep(10);

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
