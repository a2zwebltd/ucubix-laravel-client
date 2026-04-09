<?php

declare(strict_types=1);

namespace Ucubix\LaravelClient\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Ucubix\LaravelClient\UcubixServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            UcubixServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'Ucubix' => \Ucubix\LaravelClient\Facades\Ucubix::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('ucubix.api_key', 'test-api-key');
        $app['config']->set('ucubix.base_url', 'https://ucubix.test/api/v1/');
    }
}
