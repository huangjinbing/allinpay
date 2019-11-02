<?php

namespace TongLian\Allinpay\Providers;

use TongLian\Allinpay\Common\AllinpayClient;
use Illuminate\Support\ServiceProvider;

class AllinpayApiProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('allinpay', function () {
            return new AllinpayClient();
        });
        $this->mergeConfig();
    }


    public function boot()
    {
        // Publish config files
        $this->publishes([
            __DIR__ . '/../config/config.php' => app()->basePath() . '/config/allinpay.php',
        ]);
    }

    /**
     * Merges user's and entrust's configs.
     *
     * @return void
     */
    private function mergeConfig()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/config.php', 'allinpay'
        );
    }
}
