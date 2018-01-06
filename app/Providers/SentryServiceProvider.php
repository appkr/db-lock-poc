<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SentryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->alias('sentry', \Raven_Client::class);
    }
}
