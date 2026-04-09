<?php

declare(strict_types=1);

namespace Ucubix\LaravelClient;

use Illuminate\Support\ServiceProvider;
use Ucubix\PhpClient\Client\UcubixClient;

class UcubixServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/ucubix.php', 'ucubix');

        $this->app->singleton(UcubixClient::class, function ($app) {
            $config = $app['config']['ucubix'];

            $client = new UcubixClient(
                apiKey: $config['api_key'],
                baseUrl: $config['base_url'],
            );

            $client->setMaxRetryOnRateLimit($config['max_retry_on_rate_limit'] ?? 3);

            return $client;
        });

        $this->app->alias(UcubixClient::class, 'ucubix');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/ucubix.php' => config_path('ucubix.php'),
            ], 'ucubix-config');
        }
    }
}
