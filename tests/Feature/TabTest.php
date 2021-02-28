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

    public function testShowIf(): void
    {
        $tabBoolean = Tab::make('Show if', [])->showIf(true);
        $tabClosure = Tab::make('Show if', [])->showIf(function () {
            return true;
        });

        self::assertTrue($tabBoolean->toArray()['shouldShow']);
        self::assertTrue($tabClosure->toArray()['shouldShow']);
    }

    public function testShowUnless(): void
    {
        $tabBoolean = Tab::make('Show unless', [])->showUnless(false);
        $tabClosure = Tab::make('Show unless', [])->showUnless(function () {
            return false;
        });

        self::assertTrue($tabBoolean->toArray()['shouldShow']);
        self::assertTrue($tabClosure->toArray()['shouldShow']);
    }

    public function testTabClass(): void
    {
        $tab = Tab::make('Tab class', []);

        self::assertEquals([], $tab->toArray()['tabClass']);

        $tab->tabClass('bg-blue-400');

        self::assertEquals([ 'bg-blue-400' ], $tab->toArray()['tabClass']);

        $tab->tabClass([
            'bg-red-600',
            'my-tab',
        ]);

        self::assertEquals([
            'bg-red-600',
            'my-tab',
        ], $tab->toArray()['tabClass']);

        $tab->addTabClass('another-class');

        self::assertEquals([
            'bg-red-600',
            'my-tab',
            'another-class',
        ], $tab->toArray()['tabClass']);

        $tab->tabClass('some-class');

        self::assertEquals([
            'some-class',
        ], $tab->toArray()['tabClass']);
    }

    public function testBodyClass(): void
    {
        $tab = Tab::make('Body class', []);

        self::assertEquals([], $tab->toArray()['bodyClass']);

        $tab->bodyClass('bg-blue-400');

        self::assertEquals([ 'bg-blue-400' ], $tab->toArray()['bodyClass']);

        $tab->bodyClass([
            'bg-red-600',
            'my-tab',
        ]);

        self::assertEquals([
            'bg-red-600',
            'my-tab',
        ], $tab->toArray()['bodyClass']);

        $tab->addBodyClass('another-class');

        self::assertEquals([
            'bg-red-600',
            'my-tab',
            'another-class',
        ], $tab->toArray()['bodyClass']);

        $tab->bodyClass('some-class');

        self::assertEquals([
            'some-class',
        ], $tab->toArray()['bodyClass']);
    }

    /**
     * @link https://github.com/eminiarts/nova-tabs/issues/145
     *
     * @dataProvider multibyteTitleProvider
     */
    public function testDoesNotCrashWithMultibyteCharactersAsTitle(string $title): void
    {
        $tab = Tab::make($title, []);

        self::assertEmpty($tab->getSlug());
    }

    /**
     * All these strings are translated from English "This is a test"
     *
     * @return iterable<string>
     */
    public function multibyteTitleProvider(): iterable
    {
        yield 'Traditional Chinese' => [ '這是一個測試' ];
        yield 'Simplified Chinese' => [ '这是一个测试' ];
        yield 'Korean' => [ '이것은 테스트입니다' ];
        yield 'Japanese' => [ 'これはテストです' ];
    }
}
