<?php

namespace NotificationChannels\SmscRu;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

class SmscRuServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->singleton(SmscRuApi::class, static function ($app) {
            return new SmscRuApi($app['config']['services.smscru']);
        });
    }
}
