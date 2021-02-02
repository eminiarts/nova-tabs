<?php

declare(strict_types=1);

namespace Eminiarts\Tabs\Tests\Feature;

use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\Tests\TestCase;

class TabsTest extends TestCase
{
    public function testCanMakeTabsObject(): void
    {
        $tabs = Tabs::make([]);

        self::assertInstanceOf(Tabs::class, $tabs);
    }
}
