<?php
declare(strict_types=1);

namespace Tests;

use
    Fyre\Lang\Lang,
    PHPUnit\Framework\TestCase;

final class LangTest extends TestCase
{

    public function testAddPath(): void
    {
        Lang::addPath('tests/lang/dir1');

        $this->assertEquals(
            'Value',
            Lang::get('test.value')
        );
    }

    public function testAddPaths(): void
    {
        Lang::addPath('tests/lang/dir1');
        Lang::addPath('tests/lang/dir2');

        $this->assertEquals(
            'Alternate',
            Lang::get('test.value')
        );
    }

    public function testAddPathsWithPrepend(): void
    {
        Lang::addPath('tests/lang/dir1');
        Lang::addPath('tests/lang/dir2', true);

        $this->assertEquals(
            'Value',
            Lang::get('test.value')
        );
    }

    public function testGet(): void
    {
        Lang::addPath('tests/lang/dir1');

        $this->assertEquals(
            'Value',
            Lang::get('test.value')
        );
    }

    public function testGetArray(): void
    {
        Lang::addPath('tests/lang/dir1');

        $this->assertEquals(
            [
                'val1' => 'Value 1',
                'val2' => 'Value 2',
                'val3' => 'Value 3'
            ],
            Lang::get('test.data')
        );
    }

    public function testGetDeep(): void
    {
        Lang::addPath('tests/lang/dir1');

        $this->assertEquals(
            'Value 1',
            Lang::get('test.data.val1')
        );
    }

    public function testGetWithData(): void
    {
        Lang::addPath('tests/lang/dir1');

        $this->assertEquals(
            'This is a test',
            Lang::get('test.message', ['test'])
        );
    }

    public function testGetPathFallback(): void
    {
        Lang::addPath('tests/lang/dir1');
        Lang::addPath('tests/lang/dir2');

        $this->assertEquals(
            'Fallback',
            Lang::get('test.fallback')
        );
    }

    public function testGetLocaleFallback(): void
    {
        Lang::setLocale('ru');
        Lang::addPath('tests/lang/dir1');

        $this->assertEquals(
            'Fallback',
            Lang::get('test.fallback')
        );
    }

    public function testGetLocaleCountry(): void
    {
        Lang::setLocale('en_au');
        Lang::addPath('tests/lang/dir2');

        $this->assertEquals(
            'Localized',
            Lang::get('test.value')
        );
    }

    public function testGetLocaleCountryCase(): void
    {
        Lang::setLocale('en_AU');
        Lang::addPath('tests/lang/dir2');

        $this->assertEquals(
            'Localized',
            Lang::get('test.value')
        );
    }

    public function testGetLocaleCountryExtended(): void
    {
        Lang::setLocale('en_au_posix');
        Lang::addPath('tests/lang/dir2');

        $this->assertEquals(
            'Localized',
            Lang::get('test.value')
        );
    }

    public function testGetInvalid(): void
    {
        Lang::addPath('tests/lang/dir1');

        $this->assertEquals(
            null,
            Lang::get('test.invalid')
        );
    }

    public function testGetLocale(): void
    {
        Lang::setLocale('ru');

        $this->assertEquals(
            'ru',
            Lang::getLocale()
        );
    }

    public function testGetLocaleDefault(): void
    {
        $this->assertEquals(
            'en',
            Lang::getLocale()
        );
    }

    public function testGetDefaultLocale(): void
    {
        Lang::setDefaultLocale('ru');

        $this->assertEquals(
            'ru',
            Lang::getDefaultLocale()
        );
    }

    protected function setUp(): void
    {
        Lang::setDefaultLocale('en');
        Lang::setLocale();
        Lang::clear();
    }

}
