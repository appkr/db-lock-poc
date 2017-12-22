<?php

namespace App\Http\Controllers\Reviews;

use App\Http\Controllers\Controller;
use App\Http\Requests\Review\CreateReviewRequest;
use App\Http\Requests\Review\DeleteReviewRequest;
use App\Http\Requests\Review\ListReviewRequest;
use App\Http\Requests\Review\UpdateReviewRequest;
use App\Transformers\ReviewTransformer;
use Appkr\Api\Http\Response as Presenter;
use Illuminate\Http\Response;
use Myshop\Application\Service\ReviewService;
use Myshop\Domain\Repository\ProductRepository;
use Myshop\Domain\Repository\ReviewRepository;

class ReviewController extends Controller
{
    private $productRepository;
    private $reviewRepository;
    private $reviewService;
    private $presenter;

    public function __construct(
        ProductRepository $productRepository,
        ReviewRepository $reviewRepository,
        ReviewService $reviewService,
        Presenter $presenter
    ) {
        $this->productRepository = $productRepository;
        $this->reviewService = $reviewService;
        $this->reviewRepository = $reviewRepository;
        $this->presenter = $presenter;
    }

    /**
     * @SWG\Definition(
     *     definition="ReviewListResponse",
     *     type="object",
     *     required={ "data", "meta" },
     *     @SWG\Property(
     *         property="data",
     *         type="array",
     *         @SWG\Items(ref="#/definitions/ReviewDto")
     *     ),
     *     @SWG\Property(
     *         property="meta",
     *         ref="#/definitions/Meta"
     *     )
     * ),
     * @SWG\Get(
     *     path="/v1/products/{productId}/reviews",
     *     operationId="listReviews",
     *     tags={"Review"},
     *     summary="상품에 대한 리뷰 목록을 조회합니다.",
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
     *         name="q",
     *         in="query",
     *         required=false,
     *         type="string",
     *         description="검색어"
     *     ),
     *     @SWG\Parameter(
     *         name="user_id",
     *         in="query",
     *         required=false,
     *         type="integer",
     *         format="int64",
     *         description="작성자 ID"
     *     ),
     *     @SWG\Parameter(
     *         name="sort_key",
     *         in="query",
     *         required=false,
     *         type="string",
     *         enum={ "CREATED_AT" },
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
     *         @SWG\Schema(ref="#/definitions/ReviewListResponse")
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         description="오류",
     *         @SWG\Schema(ref="#/definitions/ErrorDto")
     *     )
     * )
     *
     * @param ListReviewRequest $request
     * @param int $productId
     * @return \Illuminate\Contracts\Http\Response
     */
    public function index(ListReviewRequest $request, int $productId)
    {
        $product = $this->productRepository->findById($productId);

        $paginatedReviewCollection = $this->reviewRepository->findBySearchParam(
            $request->getReviewSearchParam(),
            $product
        );

        return $this->presenter->withPagination(
            $paginatedReviewCollection,
            new ReviewTransformer
        );
    }

    /**
     * @SWG\Post(
     *     path="/v1/products/{productId}/reviews",
     *     operationId="createReview",
     *     tags={"Review"},
     *     summary="새 리뷰를 등록합니다.",
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
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/NewReviewRequest"),
     *     ),
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=201,
     *         description="성공",
     *         @SWG\Schema(ref="#/definitions/ReviewDto")
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         description="오류",
     *         @SWG\Schema(ref="#/definitions/ErrorDto")
     *     )
     * )
     *
     * @param CreateReviewRequest $request
     * @param int $productId
     * @return \Illuminate\Contracts\Http\Response
     */
    public function store(CreateReviewRequest $request, int $productId)
    {
        $product = $this->productRepository->findById($productId);

        $review = $this->reviewService->createReview(
            $product, $request->user(), $request->getReviewDto()
        );

        return $this->presenter
            ->setStatusCode(Response::HTTP_CREATED)
            ->withItem($review, new ReviewTransformer);
    }

    /**
     * @SWG\Put(
     *     path="/v1/products/{productId}/reviews/{reviewId}",
     *     operationId="updateReview",
     *     tags={"Review"},
     *     summary="리뷰를 수정합니다.",
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
     *         name="reviewId",
     *         in="path",
     *         type="integer",
     *         format="int64",
     *         required=true
     *     ),
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         required=false,
     *         @SWG\Schema(ref="#/definitions/NewReviewRequest"),
     *     ),
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="성공",
     *         @SWG\Schema(ref="#/definitions/ReviewDto")
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         description="오류",
     *         @SWG\Schema(ref="#/definitions/ErrorDto")
     *     )
     * )
     *
     * @param UpdateReviewRequest $request
     * @param int $productId
     * @param int $reviewId
     * @return \Illuminate\Contracts\Http\Response
     */
    public function update(
        UpdateReviewRequest $request,
        int $productId,
        int $reviewId
    ) {
        $product = $this->productRepository->findById($productId);

        // [선점잠금] 레코드를 조회하고 잠급니다.
        // $review = $this->reviewRepository->findByIdWithLock($reviewId, $product);

        // [선점잠금] PoC를 위해 강제로 잠금을 연장합니다.
        // 선점한 프로세스 A가 끝나고 DB 잠금이 풀리면, 다음 프로세스 B를 처리합니다.
        // sleep(10);

        // [비선점잠금]
        // 조회시점 대비 DB의 버전이 같은지를 확인하여 변경 가능 여부를 판단합니다.
        $review = $this->reviewRepository->findById($reviewId, $product);

        // Request에서는 리뷰 작성자를 식별할 수 없어서 컨트롤러에서 접근 권한 검사 합니다.
        $this->authorize('update', $review);

        $review = $this->reviewService->modifyReview(
            $review, $request->getReviewDto()
        );

        return $this->presenter->withItem($review, new ReviewTransformer);
    }

    /**
     * @SWG\Delete(
     *     path="/v1/products/{productId}/reviews/{reviewId}",
     *     operationId="deleteReview",
     *     tags={"Review"},
     *     summary="리뷰를 삭제합니다.",
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
     *         name="reviewId",
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
     * @param DeleteReviewRequest $request
     * @param int $productId
     * @param int $reviewId
     * @return \Illuminate\Contracts\Http\Response
     */
    public function destroy(
        DeleteReviewRequest $request,
        int $productId,
        int $reviewId
    ) {
        $product = $this->productRepository->findById($productId);
        $review = $this->reviewRepository->findById($reviewId);

        // Request에서는 리뷰 작성자를 식별할 수 없어서 컨트롤러에서 접근 권한 검사 합니다.
        $this->authorize('delete', $review);

        $this->reviewService->deleteReview($review, $product);

        return $this->presenter->setStatusCode(Response::HTTP_NO_CONTENT)->respond([]);
    }
}
