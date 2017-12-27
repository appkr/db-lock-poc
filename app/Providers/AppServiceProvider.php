<?php

namespace App\Providers;

use App\Support\GreaterThanOtherValidator;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\ServiceProvider;
use Myshop\Domain\Model\User;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Validator::extend(
            'greater_than_other',
            GreaterThanOtherValidator::class . '@validate',
            ':attribute의 값이 잘못되었습니다.'
        );
    }

    public function register()
    {
        $this->app->bind(UserProvider::class, function ($app) {
            $hasher = $app[Hasher::class];
            $userModelClass = User::class;

            return new EloquentUserProvider($hasher, $userModelClass);
        });
    }
}
