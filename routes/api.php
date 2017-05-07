<?php

use Illuminate\Database\Events\QueryExecuted;

DB::listen(function (QueryExecuted $query) {
    Log::info($query->sql, $query->bindings);
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
