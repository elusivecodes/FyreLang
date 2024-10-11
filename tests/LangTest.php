<?php
declare(strict_types=1);

namespace Tests;

use Fyre\Lang\Lang;
use Fyre\Utility\Path;
use PHPUnit\Framework\TestCase;

final class LangTest extends TestCase
{
    public function testAddPath(): void
    {
        Lang::addPath('tests/lang/dir1');

        $this->assertSame(
            'Value',
            Lang::get('test.value')
        );
    }

    public function testAddPathDuplicate(): void
    {
        Lang::addPath('tests/lang/dir1');
        Lang::addPath('tests/lang/dir2');
        Lang::addPath('tests/lang/dir1');

        $this->assertSame(
            [
                Path::resolve('tests/lang/dir1'),
                Path::resolve('tests/lang/dir2'),
            ],
            Lang::getPaths()
        );
    }

    public function testAddPathPrependDuplicate(): void
    {
        Lang::addPath('tests/lang/dir1');
        Lang::addPath('tests/lang/dir2');
        Lang::addPath('tests/lang/dir2', true);

        $this->assertSame(
            [
                Path::resolve('tests/lang/dir1'),
                Path::resolve('tests/lang/dir2'),
            ],
            Lang::getPaths()
        );
    }

    public function testAddPaths(): void
    {
        Lang::addPath('tests/lang/dir1');
        Lang::addPath('tests/lang/dir2');

        $this->assertSame(
            'Alternate',
            Lang::get('test.value')
        );
    }

    public function testAddPathsWithPrepend(): void
    {
        Lang::addPath('tests/lang/dir1');
        Lang::addPath('tests/lang/dir2', true);

        $this->assertSame(
            'Value',
            Lang::get('test.value')
        );
    }

    public function testGet(): void
    {
        Lang::addPath('tests/lang/dir1');

        $this->assertSame(
            'Value',
            Lang::get('test.value')
        );
    }

    public function testGetArray(): void
    {
        Lang::addPath('tests/lang/dir1');

        $this->assertSame(
            [
                'val1' => 'Value 1',
                'val2' => 'Value 2',
                'val3' => 'Value 3',
            ],
            Lang::get('test.data')
        );
    }

    public function testGetDeep(): void
    {
        Lang::addPath('tests/lang/dir1');

        $this->assertSame(
            'Value 1',
            Lang::get('test.data.val1')
        );
    }

    public function testGetDefaultLocale(): void
    {
        Lang::setDefaultLocale('ru');

        $this->assertSame(
            'ru',
            Lang::getDefaultLocale()
        );
    }

    public function testGetInvalid(): void
    {
        Lang::addPath('tests/lang/dir1');

        $this->assertNull(
            Lang::get('test.invalid')
        );
    }

    public function testGetLocale(): void
    {
        Lang::setLocale('ru');

        $this->assertSame(
            'ru',
            Lang::getLocale()
        );
    }

    public function testGetLocaleCountry(): void
    {
        Lang::setLocale('en_au');
        Lang::addPath('tests/lang/dir2');

        $this->assertSame(
            'Localized',
            Lang::get('test.value')
        );
    }

    public function testGetLocaleCountryCase(): void
    {
        Lang::setLocale('en_AU');
        Lang::addPath('tests/lang/dir2');

        $this->assertSame(
            'Localized',
            Lang::get('test.value')
        );
    }

    public function testGetLocaleCountryExtended(): void
    {
        Lang::setLocale('en_au_posix');
        Lang::addPath('tests/lang/dir2');

        $this->assertSame(
            'Localized',
            Lang::get('test.value')
        );
    }

    public function testGetLocaleDefault(): void
    {
        $this->assertSame(
            'en',
            Lang::getLocale()
        );
    }

    public function testGetLocaleFallback(): void
    {
        Lang::setLocale('ru');
        Lang::addPath('tests/lang/dir1');

        $this->assertSame(
            'Fallback',
            Lang::get('test.fallback')
        );
    }

    public function testGetPathFallback(): void
    {
        Lang::addPath('tests/lang/dir1');
        Lang::addPath('tests/lang/dir2');

        $this->assertSame(
            'Fallback',
            Lang::get('test.fallback')
        );
    }

    public function testGetWithData(): void
    {
        Lang::addPath('tests/lang/dir1');

        $this->assertSame(
            'This is a test',
            Lang::get('test.message', ['test'])
        );
    }

    public function testRemovePath(): void
    {
        Lang::addPath('tests/lang/dir1');

        $this->assertTrue(
            Lang::removePath('tests/lang/dir1')
        );

        $this->assertEmpty(
            Lang::getPaths()
        );
    }

    public function testRemovePathInvalid(): void
    {
        $this->assertFalse(
            Lang::removePath('tests/lang/dir1')
        );
    }

    public function testSetDefaultLocaleCallback(): void
    {
        Lang::setDefaultLocale(fn(): string => 'ru');

        $this->assertSame(
            'ru',
            Lang::getDefaultLocale()
        );
    }

    public function testSetLocaleCallback(): void
    {
        Lang::setLocale(fn(): string => 'ru');

        $this->assertSame(
            'ru',
            Lang::getLocale()
        );
    }

    protected function setUp(): void
    {
        Lang::setDefaultLocale('en');
        Lang::setLocale();
        Lang::clear();
    }
}
