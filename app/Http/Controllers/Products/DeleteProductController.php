<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\DeleteProductRequest;
use DB;
use Exception;
use Illuminate\Http\Response;
use Myshop\Application\Service\ProductService;
use Myshop\Domain\Repository\ProductRepository;

class DeleteProductController extends Controller
{
    final public function __invoke(
        DeleteProductRequest $request,
        ProductRepository $repository,
        ProductService $service,
        int $productId
    ) {
        $product = $repository->findById($productId);

        DB::beginTransaction();

        try {
            $service->deleteProduct($product);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
