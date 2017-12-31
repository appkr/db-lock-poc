<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Log;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [];

    public function boot()
    {
        parent::boot();

//        Event::listen('eloquent.*', function ($event) {
//            Log::info($event);
//        });
    }
}
