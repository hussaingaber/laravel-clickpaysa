<?php

declare(strict_types=1);

namespace GranadaPride\Clickpay;

use GranadaPride\Clickpay\Contracts\HttpClientInterface;
use GranadaPride\Clickpay\Contracts\PaymentGatewayInterface;
use Illuminate\Support\ServiceProvider;

class ClickpayServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(HttpClientInterface::class, function () {
            return new HttpClient(
                config('clickpay.server_key'),
                config('clickpay.base_url')
            );
        });

        $this->app->singleton(PaymentGatewayInterface::class, function ($app) {
            return new ClickpayClient(
                $app->make(HttpClientInterface::class),
                config('clickpay.profile_id'),
                config('clickpay.currency')
            );
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
        ], 'clickpay');
    }
}
