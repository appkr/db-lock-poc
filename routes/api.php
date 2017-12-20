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

Route::get('api', WelcomeController::class);

Route::prefix('api/auth')->middleware([ThrottleRequests::class . ':60,1'])->group(function () {
    // 로그인
    Route::post('login', LoginController::class);

    Route::middleware([ValidateApiUser::class])->group(function () {
        // 로그아웃
        Route::post('logout', LogoutController::class);
        // 토큰 갱신
        Route::post('refresh', RefreshController::class);
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
