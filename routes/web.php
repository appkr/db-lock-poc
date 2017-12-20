<?php

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Database\Events\QueryExecuted;

DB::listen(function (QueryExecuted $query) {
    // TODO @appkr 디버깅 목적으로 남겨 둠.
    Log::info($query->sql, $query->bindings);
});

Route::get('/', 'HealthController')->name('health');

Auth::routes();

Route::get('home', 'HomeController@index')
    ->middleware(Authenticate::class)
    ->name('home');
