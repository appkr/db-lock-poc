<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Myshop\Domain\Repository\PermissionRepository;
use Myshop\Domain\Repository\ProductRepository;
use Myshop\Domain\Repository\ReviewRepository;
use Myshop\Domain\Repository\RoleRepository;
use Myshop\Domain\Repository\UserRepository;
use Myshop\Infrastructure\Eloquent\EloquentPermissionRepository;
use Myshop\Infrastructure\Eloquent\EloquentProductRepository;
use Myshop\Infrastructure\Eloquent\EloquentReviewRepository;
use Myshop\Infrastructure\Eloquent\EloquentRoleRepository;
use Myshop\Infrastructure\Eloquent\EloquentUserRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //
    }

    public function register()
    {
        $this->app->bind(
            UserRepository::class,
            EloquentUserRepository::class
        );

        $this->app->bind(
            ProductRepository::class,
            EloquentProductRepository::class
        );

        $this->app->bind(
            ReviewRepository::class,
            EloquentReviewRepository::class
        );

        $this->app->bind(
            RoleRepository::class,
            EloquentRoleRepository::class
        );

        $this->app->bind(
            PermissionRepository::class,
            EloquentPermissionRepository::class
        );
    }
}
