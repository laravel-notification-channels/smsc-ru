<?php

namespace NotificationChannels\SmscRu;

use Illuminate\Support\ServiceProvider;

class SmscRuServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(SmscRuApi::class, function ($app) {
            return new SmscRuApi($app['config']['services.smscru']);
        });
    }
}
