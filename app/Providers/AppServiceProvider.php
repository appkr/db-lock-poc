<?php

namespace App\Providers;

use App\Support\GreaterThanOtherValidator;
use Illuminate\Support\ServiceProvider;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend(
            'greater_than_other',
            GreaterThanOtherValidator::class . '@validate',
            ':attribute의 값이 잘못되었습니다.'
        );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
