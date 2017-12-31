<?php

namespace App\Providers;

use App\Policies\ReviewPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Myshop\Domain\Model\Review;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // NOTE. 컨트롤러@메서드 형식을 따르지 않으므로 Policy 대신 미들웨어를 이용합니다.
        // Product::class => ProductPolicy::class,
        Review::class => ReviewPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
