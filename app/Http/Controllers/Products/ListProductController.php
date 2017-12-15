<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ListProductRequest;
use App\Transformers\ProductTransformer;
use Appkr\Api\Http\Response as Presenter;
use Myshop\Domain\Repository\ProductRepository;

class ListProductController extends Controller
{
    /**
     * @SWG\Definition(
     *     definition="ProductListResponse",
     *     type="object",
     *     required={ "data", "meta" },
     *     @SWG\Property(
     *         property="data",
     *         type="array",
     *         @SWG\Items(ref="#/definitions/ProductDto")
     *     ),
     *     @SWG\Property(
     *         property="meta",
     *         ref="#/definitions/Meta"
     *     )
     * ),
     * @SWG\Get(
     *     path="/v1/products",
     *     operationId="listProducts",
     *     tags={"Product"},
     *     summary="상품 목록을 조회합니다.",
     *     @SWG\Parameter(
     *         name="Authorization",
     *         in="header",
     *         required=true,
     *         type="string",
     *         description="액세스 토큰",
     *         default="Bearer "
     *     ),
     *     @SWG\Parameter(
     *         name="q",
     *         in="query",
     *         required=false,
     *         type="string",
     *         description="검색어"
     *     ),
     *     @SWG\Parameter(
     *         name="price_from",
     *         in="query",
     *         required=false,
     *         type="integer",
     *         format="int64",
     *         description="최소 가격"
     *     ),
     *     @SWG\Parameter(
     *         name="price_to",
     *         in="query",
     *         required=false,
     *         type="integer",
     *         format="int64",
     *         description="최대 가격"
     *     ),
     *     @SWG\Parameter(
     *         name="sort_key",
     *         in="query",
     *         required=false,
     *         type="string",
     *         enum={"CREATED_AT", "PRICE", "STOCK"},
     *         default="CREATED_AT",
     *         description="정렬 필드"
     *     ),
     *     @SWG\Parameter(
     *         name="sort_direction",
     *         in="query",
     *         required=false,
     *         type="string",
     *         enum={"ASC", "DESC"},
     *         default="DESC",
     *         description="정렬 방향"
     *     ),
     *     @SWG\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         type="integer",
     *         format="int32",
     *         default=1,
     *         description="페이지"
     *     ),
     *     @SWG\Parameter(
     *         name="size",
     *         in="query",
     *         required=false,
     *         type="integer",
     *         format="int32",
     *         default=10,
     *         description="페이지당 표시할 아이템 개수"
     *     ),
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="OK",
     *         @SWG\Schema(ref="#/definitions/ProductListResponse")
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         description="오류",
     *         @SWG\Schema(ref="#/definitions/ErrorDto")
     *     )
     * )
     *
     * @param ListProductRequest $request
     * @param ProductRepository $repository
     * @param Presenter $presenter
     * @return \Illuminate\Contracts\Http\Response
     */
    final public function __invoke(
        ListProductRequest $request,
        ProductRepository $repository,
        Presenter $presenter
    ) {
        $paginatedProductCollection = $repository->findBySearchParam(
            $request->getProductSearchParam()
        );

        return $presenter->withPagination(
            $paginatedProductCollection,
            new ProductTransformer
        );
    }
}
