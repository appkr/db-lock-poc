<?php

use App\Http\Middleware\ValidateApiUser;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Routing\Middleware\ThrottleRequests;

DB::listen(function (QueryExecuted $query) {
    Log::info($query->sql, $query->bindings);
});

Route::prefix('auth')->middleware([ThrottleRequests::class.':60,1'])->group(function () {
    // 로그인
    Route::post('login', 'ApiAuth\LoginController');

    Route::middleware([ValidateApiUser::class])->group(function () {
        // 로그아웃
        Route::post('logout', 'ApiAuth\LogoutController');
        // 토큰 갱신
        Route::post('refresh', 'ApiAuth\RefreshController');
        // 내 정보
        Route::post('me', 'ApiAuth\MeController');
    });
});

Route::prefix('v1')->group(function () {
    Route::get('/', 'WelcomeController@welcome');
    Route::resource('products', 'ProductController', [
        'only' => ['index', 'store', 'update', 'destroy'],
    ]);
    Route::resource('products.reviews', 'ReviewController', [
        'only' => ['index', 'store', 'update', 'destroy'],
    ]);
});
