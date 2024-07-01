<?php

declare(strict_types=1);

namespace BBSLab\Tabs\Tests\Feature;

use BBSLab\Tabs\Tabs;
use BBSLab\Tabs\Tests\TestCase;

class TabsTest extends TestCase
{
    public function testCanMakeTabsObject(): void
    {
        $tabs = Tabs::make([]);

        self::assertInstanceOf(Tabs::class, $tabs);
    }
}
