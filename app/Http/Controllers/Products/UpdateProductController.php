<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Transformers\ProductTransformer;
use Appkr\Api\Http\Response;
use DB;
use Exception;
use Myshop\Application\Service\ProductService;
use Myshop\Domain\Repository\ProductRepository;

class UpdateProductController extends Controller
{
    final public function __invoke(
        UpdateProductRequest $request,
        ProductRepository $repository,
        ProductService $service,
        Response $presenter,
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
            sleep(10);

            $product = $service->modifyProduct($product, $request->getProductDto());

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $presenter->withItem($product, new ProductTransformer);
    }
}
