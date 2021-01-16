<?php

declare(strict_types=1);

namespace Eminiarts\Tabs\Tests\Feature;

use Eminiarts\Tabs\Tab;
use PHPUnit\Framework\TestCase;

class TabTest extends TestCase
{
    public function testCanInstantiateWithConstructor(): void
    {
        $tab = new Tab('Test Tab', []);

        self::assertSame('Test Tab', $tab->toArray()['title']);
    }

    public function testCanInstantiateThroughMake(): void
    {
        $tab = Tab::make('Test Tab', []);

        self::assertSame('Test Tab', $tab->toArray()['title']);
    }
}
