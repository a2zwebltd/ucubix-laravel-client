<?php

declare(strict_types=1);

namespace Ucubix\LaravelClient\Tests;

use Ucubix\LaravelClient\Facades\Ucubix;
use Ucubix\PhpClient\Client\UcubixClient;

class ServiceProviderTest extends TestCase
{
    public function test_client_is_singleton(): void
    {
        $a = $this->app->make(UcubixClient::class);
        $b = $this->app->make(UcubixClient::class);

        $this->assertInstanceOf(UcubixClient::class, $a);
        $this->assertSame($a, $b);
    }

    public function test_alias_resolves_client(): void
    {
        $this->assertInstanceOf(UcubixClient::class, $this->app->make('ucubix'));
    }

    public function test_config_is_merged(): void
    {
        $this->assertSame('test-api-key', config('ucubix.api_key'));
        $this->assertSame('https://ucubix.test/api/v1/', config('ucubix.base_url'));
        $this->assertSame(3, config('ucubix.max_retry_on_rate_limit'));
    }

    public function test_facade_resolves_client(): void
    {
        $this->assertInstanceOf(UcubixClient::class, Ucubix::getFacadeRoot());
    }

    public function test_max_retry_configured(): void
    {
        $this->assertSame(3, Ucubix::getMaxRetryOnRateLimit());
    }

    public function test_rate_limit_via_facade(): void
    {
        $this->assertNull(Ucubix::getRateLimitRemaining());
        $this->assertNull(Ucubix::getRateLimitLimit());
    }

    public function test_set_max_retry_via_facade(): void
    {
        Ucubix::setMaxRetryOnRateLimit(7);

        $this->assertSame(7, Ucubix::getMaxRetryOnRateLimit());
    }

    public function test_custom_max_retry_from_config(): void
    {
        $this->app['config']->set('ucubix.max_retry_on_rate_limit', 5);
        $this->app->forgetInstance(UcubixClient::class);

        $this->assertSame(5, $this->app->make(UcubixClient::class)->getMaxRetryOnRateLimit());
    }
}
