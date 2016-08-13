<?php

namespace NotificationChannels\SmscRu;

use Illuminate\Support\ServiceProvider;

class SmscRuServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(SmscRuApi::class, function () {
            $config = config('services.smscru');

            return new SmscRuApi($config['login'], $config['secret'], $config['sender']);
        });
    }
}
