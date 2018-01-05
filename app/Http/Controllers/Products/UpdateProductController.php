<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Exception\ConflictException;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Transformers\ProductTransformer;
use Appkr\Api\Http\Response as Presenter;
use DB;
use Exception;
use Myshop\Application\Service\ProductService;
use Myshop\Domain\Repository\ProductRepository;
use Myshop\Infrastructure\Exception\OptimisticLockingFailureException;

class UpdateProductController extends Controller
{
    /**
     * @SWG\Put(
     *     path="/v1/products/{productId}",
     *     operationId="updateProduct",
     *     tags={"Product"},
     *     summary="상품을 수정합니다.",
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
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         required=false,
     *         @SWG\Schema(ref="#/definitions/NewProductRequest"),
     *     ),
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
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
     * @param UpdateProductRequest $request
     * @param ProductRepository $repository
     * @param ProductService $service
     * @param Presenter $presenter
     * @param int $productId
     * @return \Illuminate\Contracts\Http\Response
     * @throws Exception
     */
    final public function __invoke(
        UpdateProductRequest $request,
        ProductRepository $repository,
        ProductService $service,
        Presenter $presenter,
        int $productId
    ) {
        DB::beginTransaction();

        try {
            // [잠금 없음] Row를 잠그지 않고 조회합니다.
            // $product = $this->productRepository->findById($productId);

            // [독점적 선점잠금] SELECT ... FOR UPDATE
            // Row를 조회하고 잠급니다. 다른 프로세스는 해당 Row를 읽을 수 없습니다.
            $product = $repository->findByIdWithExclusiveLock($productId);

            // [공유된 선점잠금] SELECT ... LOCK IN SHARE MODE
            // Row를 조회하고 잠금니다. 다른 프로세스는 해당 Row를 읽을 수 있으나 변경할 수 없습니다.
            // $product = $this->productRepository->findByIdWithSharedLock($productId);

            // [비선점잠금]
            // 조회시점 대비 다른 프로세스에의해 데이터가 이미 변경되었는지 확인한 후 변경합니다.
            // @see core/Myshop/Application/Service/ProductService.php: 42
            // @see core/Myshop/Infrastructure/Eloquent/EloquentProductRepository.php: 56

            // 시간이 오래 걸리는 작업을 시뮬레이션하기 위해 강제로 10초 지연을 줬습니다.
            // sleep(10);

            $product = $service->modifyProduct($product, $request->getProductDto());
            DB::commit();
        } catch (OptimisticLockingFailureException $e) {
            DB::rollBack();
            throw new ConflictException($e);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $presenter->withItem($product, new ProductTransformer);
    }
}
