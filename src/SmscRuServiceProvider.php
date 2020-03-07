<?php

namespace NotificationChannels\SmscRu;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class SmscRuServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->singleton(SmscRuApi::class, static function ($app) {
            return new SmscRuApi($app['config']['services.smscru']);
        });
    }

    public function provides(): array
    {
        return [
            SmscRuApi::class,
        ];
    }
}
