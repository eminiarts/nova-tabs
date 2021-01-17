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

    public function testResolvesNameCorrectly(): void
    {
        $tabWithoutName = Tab::make('My awesome tab', []);
        $tabWithName = Tab::make('My awesome tab with a name', [])->name('Tab name');

        self::assertSame('My awesome tab', $tabWithoutName->toArray()['name']);
        self::assertSame('my-awesome-tab', $tabWithoutName->toArray()['slug']);
        self::assertSame('Tab name', $tabWithName->toArray()['name']);
        self::assertSame('tab-name', $tabWithName->toArray()['slug']);
    }
}
