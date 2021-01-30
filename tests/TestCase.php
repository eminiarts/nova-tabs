<?php

declare(strict_types=1);

namespace Eminiarts\Tabs\Tests;

use Eminiarts\Tabs\TabsServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            TabsServiceProvider::class,
        ];
    }
}
