<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\DeleteProductRequest;
use Appkr\Api\Http\Response as Presenter;
use DB;
use Exception;
use Illuminate\Http\Response;
use Myshop\Application\Service\ProductService;
use Myshop\Domain\Repository\ProductRepository;

class DeleteProductController extends Controller
{
    /**
     * @SWG\Delete(
     *     path="/v1/products/{productId}",
     *     operationId="deleteProduct",
     *     tags={"Product"},
     *     summary="상품을 삭제합니다.",
     *     consumes={"application/json", "application/x-www-form-urlencoded"},
     *     @SWG\Parameter(
     *         name="Authorization",
     *         in="header",
     *         required=true,
     *         type="string",
     *         description="액세스 토큰",
     *         default="Bearer "
     *     ),
     *     @SWG\Parameter(
     *         name="productId",
     *         in="path",
     *         type="integer",
     *         format="int64",
     *         required=true
     *     ),
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=204,
     *         description="성공",
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         description="오류",
     *         @SWG\Schema(ref="#/definitions/ErrorDto")
     *     )
     * )
     *
     * @param DeleteProductRequest $request
     * @param ProductRepository $repository
     * @param ProductService $service
     * @param Presenter $presenter
     * @param int $productId
     * @return \Illuminate\Contracts\Http\Response
     * @throws Exception
     */
    final public function __invoke(
        DeleteProductRequest $request,
        ProductRepository $repository,
        ProductService $service,
        Presenter $presenter,
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

        return $presenter->setStatusCode(Response::HTTP_NO_CONTENT)->respond([]);
    }
}
