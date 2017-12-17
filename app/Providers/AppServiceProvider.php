<?php

namespace App\Providers;

use App\Support\GreaterThanOtherValidator;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\ServiceProvider;
use Myshop\Domain\Model\User;
use Myshop\Domain\Repository\ProductRepository;
use Myshop\Domain\Repository\ReviewRepository;
use Myshop\Infrastructure\Eloquent\EloquentProductRepository;
use Myshop\Infrastructure\Eloquent\EloquentReviewRepository;
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
        $this->app->bind(UserProvider::class, function ($app) {
            $hasher = $app[Hasher::class];
            $userModelClass = User::class;

            return new EloquentUserProvider($hasher, $userModelClass);
        });

        $this->app->bind(
            ProductRepository::class,
            EloquentProductRepository::class
        );

        $this->app->bind(
            ReviewRepository::class,
            EloquentReviewRepository::class
        );
    }
}
