<?php

declare(strict_types=1);

namespace GranadaPride\Clickpay;

use Illuminate\Support\ServiceProvider;

class ClickpayServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('clickpay', function ($app) {
            return new Clickpay();
        });

        $this->mergeConfigFrom(
            __DIR__ . '/../config/clickpay.php',
            'clickpay'
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/clickpay.php' => config_path('clickpay.php'),
        ], 'clickpay-config');
    }
}
