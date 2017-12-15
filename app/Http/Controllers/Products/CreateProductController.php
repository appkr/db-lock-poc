<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateProductRequest;
use App\Transformers\ProductTransformer;
use Appkr\Api\Http\Response as Presenter;
use DB;
use Exception;
use Illuminate\Http\Response;
use Myshop\Application\Service\ProductService;

class CreateProductController extends Controller
{
    /**
     * @SWG\Post(
     *     path="/v1/products",
     *     operationId="createProduct",
     *     tags={"Product"},
     *     summary="새 상품을 등록합니다.",
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
     *         name="body",
     *         in="body",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/NewProductRequest"),
     *     ),
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=201,
     *         description="성공",
     *         @SWG\Schema(ref="#/definitions/ProductDto")
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         description="오류",
     *         @SWG\Schema(ref="#/definitions/ErrorDto")
     *     )
     * )
     *
     * @param CreateProductRequest $request
     * @param ProductService $service
     * @param Presenter $presenter
     * @return \Illuminate\Contracts\Http\Response
     * @throws Exception
     */
    final public function __invoke(
        CreateProductRequest $request,
        ProductService $service,
        Presenter $presenter
    ) {
        DB::beginTransaction();

        try {
            $product = $service->createProduct($request->getProductDto());
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $presenter
            ->setStatusCode(Response::HTTP_CREATED)
            ->withItem($product, new ProductTransformer);
    }
}
