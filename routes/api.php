<?php

Route::prefix('v1')->group(function () {
    Route::resource('products', 'ProductController', [
        'only' => ['index', 'store', 'update', 'destroy'],
    ]);
    Route::resource('products.reviews', 'ReviewController', [
        'only' => ['index', 'store', 'update', 'destroy'],
    ]);
});
