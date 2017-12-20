<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateProductRequest;
use App\Transformers\ProductTransformer;
use Appkr\Api\Http\Response;
use DB;
use Exception;
use Myshop\Application\Service\ProductService;

class CreateProductController extends Controller
{
    final public function __invoke(
        CreateProductRequest $request,
        ProductService $service,
        Response $presenter
    ) {
        DB::beginTransaction();

        try {
            $product = $service->createProduct($request->getProductDto());
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $presenter->withItem($product, new ProductTransformer);
    }
}
