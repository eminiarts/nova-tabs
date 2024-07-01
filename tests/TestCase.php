<?php

declare(strict_types=1);

namespace BBSLab\Tabs\Tests;

use BBSLab\Tabs\TabsServiceProvider;
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
