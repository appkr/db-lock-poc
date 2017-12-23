<?php

use App\Http\Controllers\ApiAuth\{
    LoginController,
    LogoutController,
    MeController,
    RefreshController
};
use App\Http\Controllers\Products\{
    ListProductController,
    CreateProductController,
    UpdateProductController,
    DeleteProductController
};
use App\Http\Controllers\Reviews\ReviewController;
use App\Http\Controllers\WelcomeController;
use App\Http\Middleware\ValidateApiUser;
use Illuminate\Routing\Middleware\ThrottleRequests;

/**
 * @SWG\Swagger(
 *     schemes={"http"},
 *     host="localhost",
 *     basePath="/api",
 *     consumes={"application/json"},
 *     produces={"application/json"},
 *     @SWG\Info(
 *         title="DB-LOCK-POC",
 *         version="1",
 *         description="라라벨에 스웨거 적용 테스트를 위한 프로젝트",
 *         @SWG\Contact(
 *             name="appkr",
 *             email="juwonkim@me.com"
 *         ),
 *         @SWG\License(
 *             name="MIT",
 *             url="https://raw.githubusercontent.com/appkr/db-lock-poc/master/LICENSE"
 *         )
 *     ),
 *     @SWG\SecurityScheme(
 *         securityDefinition="JWT",
 *         type="apiKey",
 *         name="Authorization",
 *         in="header",
 *         description=""
 *     )
 * ),
 * @SWG\Definition(
 *     definition="AccessToken",
 *     type="object",
 *     required={ "access_token", "token_type", "expires_in" },
 *     @SWG\Property(
 *         property="access_token",
 *         type="string",
 *         description="액세스 토큰",
 *         example="header.body.signature"
 *     ),
 *     @SWG\Property(
 *         property="token_type",
 *         type="string",
 *         enum={"bearer"},
 *         description="토큰 인증 스킴",
 *         example="bearer"
 *     ),
 *     @SWG\Property(
 *         property="expires_in",
 *         type="integer",
 *         format="int32",
 *         description="만료까지 남은 시간(초)",
 *         example=3600
 *     )
 * ),
 * @SWG\Definition(
 *     definition="Timestamp",
 *     @SWG\Property(
 *         property="created_at",
 *         type="string",
 *         description="최초 생성 시각",
 *         example="2017-03-01T00:00:00+0900"
 *     ),
 *     @SWG\Property(
 *         property="updated_at",
 *         type="string",
 *         description="최종 수정 시각",
 *         example="2017-03-01T00:00:00+0900"
 *     )
 * ),
 * @SWG\Definition(
 *     definition="PaginatorLink",
 *     type="object",
 *     @SWG\Property(
 *         property="previous",
 *         type="string",
 *         description="다음 페이지",
 *         example="http://host/path?page=3"
 *     ),
 *     @SWG\Property(
 *         property="next",
 *         type="string",
 *         description="이전 페이지",
 *         example="http://host/path?page=1"
 *     )
 * ),
 * @SWG\Definition(
 *     definition="Paginator",
 *     type="object",
 *     required={ "total", "count", "per_page", "current_page", "total_pages", "links" },
 *     @SWG\Property(
 *         property="total",
 *         type="integer",
 *         format="int32",
 *         description="총 리소스 개수",
 *         example=1000
 *     ),
 *     @SWG\Property(
 *         property="count",
 *         type="integer",
 *         format="int32",
 *         description="현재 페이지에 표시된 리소스 개수",
 *         example=10
 *     ),
 *     @SWG\Property(
 *         property="per_page",
 *         type="integer",
 *         property="per_page",
 *         format="int32",
 *         description="페이지당 리소스 표시 개수",
 *         example=10
 *     ),
 *     @SWG\Property(
 *         property="current_page",
 *         type="integer",
 *         property="current_page",
 *         format="int32",
 *         description="현재 페이지 번호",
 *         example=2
 *     ),
 *     @SWG\Property(
 *         property="total_pages",
 *         type="integer",
 *         property="total_pages",
 *         format="int32",
 *         description="총 페이지 수",
 *         example=100
 *     ),
 *     @SWG\Property(
 *         property="links",
 *         ref="#/definitions/PaginatorLink"
 *     )
 * ),
 * @SWG\Definition(
 *     definition="Meta",
 *     type="object",
 *     required={ "pagination" },
 *     @SWG\Property(
 *         property="pagination",
 *         ref="#/definitions/Paginator"
 *     )
 * )
 */
Route::get('api', WelcomeController::class);

Route::prefix('api/auth')->middleware([ThrottleRequests::class.':60,1'])->group(function () {
    // 로그인
    Route::post('login', LoginController::class);
    // 토큰 갱신
    Route::post('refresh', RefreshController::class);

    Route::middleware([ValidateApiUser::class])->group(function () {
        // 로그아웃
        Route::post('logout', LogoutController::class);
        // 내 정보
        Route::post('me', MeController::class);
    });
});

Route::prefix('api/v1')->middleware([
    ThrottleRequests::class . ':60,1',
    ValidateApiUser::class,
])->group(function () {
    Route::middleware([ValidateApiUser::class])->group(function () {
        // 상품 라우트
        Route::prefix('products')->group(function () {
            // 상품 목록
            Route::get('/', ListProductController::class);
            // 상품 등록
            Route::post('/', CreateProductController::class);
            // 상품 수정
            Route::put('{productId}', UpdateProductController::class);
            // 상품 삭제
            Route::delete('{productId}', DeleteProductController::class);
        });

        // 리뷰 라우트
        Route::resource('products.reviews', ReviewController::class, [
            'only' => ['index', 'store', 'update', 'destroy'],
        ]);
    });
});
