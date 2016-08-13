<?php

namespace NotificationChannels\SmscRu;

use Illuminate\Support\ServiceProvider;

class SmscRuServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(SmscRuApi::class, function ($app) {
            $config = $app['config']['services.smscentre'];

            return new SmscRuApi($config['login'], $config['secret'], $config['sender']);
        });
    }
}
