<?php

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Database\Events\QueryExecuted;
use Myshop\Domain\Model\Product;

DB::listen(function (QueryExecuted $query) {
    // TODO @appkr 디버깅 목적으로 남겨 둠.
    Log::info($query->sql, $query->bindings);
});

function logTestResult(string $token, array $context) {
    Log::info($token, array_merge($context, [
        'cpu%' => sys_getloadavg()[0],
        'memoryInMB' => memory_get_usage() / 1000000,
        'phpUsedTimeInMS' => (microtime(true) - LARAVEL_START) * 1000,
    ]));
}

Route::get('get', function () {
    $loopCount = 0;
    $products = Product::query()->get();
    foreach ($products as $product) {
        ++$loopCount; usleep(1);
    }
    logTestResult('get', ['loopCount' => $loopCount]);
});

Route::get('get-with-higher-order-function', function () {
    $loopCount = 0;
    $products = Product::query()->get();
    $products->each(function (Product $product) use (&$loopCount) {
        ++$loopCount; usleep(1);
    });
    logTestResult('get-with-higher-order-function', ['loopCount' => $loopCount]);
});

Route::get('chunk', function () {
    $loopCount = 0;
    Product::query()->chunk(1000, function ($products) use (&$loopCount) {
        foreach ($products as $product) {
            ++$loopCount; usleep(1);
        }
    });
    logTestResult('chunk', ['loopCount' => $loopCount]);
});

Route::get('cursor', function () {
    $loopCount = 0;
    $products = Product::query();
    foreach ($products->cursor() as $product) {
        ++$loopCount; usleep(1);
    }
    logTestResult('cursor', ['loopCount' => $loopCount]);
});

Route::get('/', 'HealthController')->name('health');

Auth::routes();

Route::get('home', 'HomeController@index')
    ->middleware(Authenticate::class)
    ->name('home');
