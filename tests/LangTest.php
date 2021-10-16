<?php
declare(strict_types=1);

namespace Tests;

use
    Fyre\Lang,
    PHPUnit\Framework\TestCase;

use function
    getcwd;

final class LangTest extends TestCase
{

    public function testLangAddPath(): void
    {
        $cwd = getcwd();

        Lang::addPath($cwd.'/tests/lang/dir1');

        $this->assertEquals(
            'Value',
            Lang::get('test.value')
        );
    }

    public function testLangAddPaths(): void
    {
        $cwd = getcwd();

        Lang::addPath($cwd.'/tests/lang/dir1');
        Lang::addPath($cwd.'/tests/lang/dir2');

        $this->assertEquals(
            'Alternate',
            Lang::get('test.value')
        );
    }

    public function testLangAddPathsWithPrepend(): void
    {
        $cwd = getcwd();

        Lang::addPath($cwd.'/tests/lang/dir1');
        Lang::addPath($cwd.'/tests/lang/dir2', true);

        $this->assertEquals(
            'Value',
            Lang::get('test.value')
        );
    }

    public function testLangGet(): void
    {
        $cwd = getcwd();

        Lang::addPath($cwd.'/tests/lang/dir1');

        $this->assertEquals(
            'Value',
            Lang::get('test.value')
        );
    }

    public function testLangGetArray(): void
    {
        $cwd = getcwd();

        Lang::addPath($cwd.'/tests/lang/dir1');

        $this->assertEquals(
            [
                'val1' => 'Value 1',
                'val2' => 'Value 2',
                'val3' => 'Value 3'
            ],
            Lang::get('test.data')
        );
    }

    public function testLangGetDeep(): void
    {
        $cwd = getcwd();

        Lang::addPath($cwd.'/tests/lang/dir1');

        $this->assertEquals(
            'Value 1',
            Lang::get('test.data.val1')
        );
    }

    public function testLangGetWithData(): void
    {
        $cwd = getcwd();

        Lang::addPath($cwd.'/tests/lang/dir1');

        $this->assertEquals(
            'This is a test',
            Lang::get('test.message', ['test'])
        );
    }

    public function testLangGetPathFallback(): void
    {
        $cwd = getcwd();

        Lang::addPath($cwd.'/tests/lang/dir1');
        Lang::addPath($cwd.'/tests/lang/dir2');

        $this->assertEquals(
            'Fallback',
            Lang::get('test.fallback')
        );
    }

    public function testLangGetLocaleFallback(): void
    {
        $cwd = getcwd();

        Lang::setLocale('ru');
        Lang::addPath($cwd.'/tests/lang/dir1');

        $this->assertEquals(
            'Fallback',
            Lang::get('test.fallback')
        );
    }

    public function testLangGetLocaleCountry(): void
    {
        $cwd = getcwd();

        Lang::setLocale('en_au');
        Lang::addPath($cwd.'/tests/lang/dir2');

        $this->assertEquals(
            'Localized',
            Lang::get('test.value')
        );
    }

    public function testLangGetLocaleCountryCase(): void
    {
        $cwd = getcwd();

        Lang::setLocale('en_AU');
        Lang::addPath($cwd.'/tests/lang/dir2');

        $this->assertEquals(
            'Localized',
            Lang::get('test.value')
        );
    }

    public function testLangGetLocaleCountryExtended(): void
    {
        $cwd = getcwd();

        Lang::setLocale('en_au_posix');
        Lang::addPath($cwd.'/tests/lang/dir2');

        $this->assertEquals(
            'Localized',
            Lang::get('test.value')
        );
    }

    public function testLangGetLocale(): void
    {
        Lang::setLocale('ru');

        $this->assertEquals(
            'ru',
            Lang::getLocale()
        );
    }

    public function testLangGetLocaleDefault(): void
    {
        $this->assertEquals(
            'en',
            Lang::getLocale()
        );
    }

    public function testLangGetDefaultLocale(): void
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
