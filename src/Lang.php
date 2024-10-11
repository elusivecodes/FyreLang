<?php
declare(strict_types=1);

namespace Fyre\Lang;

use Closure;
use Fyre\Utility\Arr;
use Fyre\Utility\Path;
use MessageFormatter;

use function array_pop;
use function array_replace_recursive;
use function array_splice;
use function array_unshift;
use function call_user_func;
use function explode;
use function file_exists;
use function implode;
use function in_array;
use function is_array;
use function locale_get_default;
use function strtok;
use function strtolower;

/**
 * Lang
 */
abstract class Lang
{
    private static Closure|string|null $defaultLocale = null;

    private static array $lang = [];

    private static Closure|string|null $locale = null;

    private static array $paths = [];

    /**
     * Add a language path.
     *
     * @param string $path The path to add.
     * @param bool $prepend Whether to prepend the path.
     */
    public static function addPath(string $path, bool $prepend = false): void
    {
        $path = Path::resolve($path);

        if (in_array($path, static::$paths)) {
            return;
        }

        if ($prepend) {
            array_unshift(static::$paths, $path);
        } else {
            static::$paths[] = $path;
        }
    }

    /**
     * Clear the language data.
     */
    public static function clear(): void
    {
        static::$paths = [];
        static::$lang = [];
    }

    /**
     * Get a language value.
     *
     * @param string $key The language key.
     * @param array $data The data to insert.
     * @return array|string|null The formatted language string.
     */
    public static function get(string $key, array $data = []): array|string|null
    {
        $file = strtok($key, '.');

        static::$lang[$file] ??= static::load($file);

        $line = Arr::getDot(static::$lang, $key);

        if (!$line || $data === [] || is_array($line)) {
            return $line;
        }

        return MessageFormatter::formatMessage(static::getLocale(), $line, $data);
    }

    /**
     * Get the default locale.
     *
     * @return string The default locale.
     */
    public static function getDefaultLocale(): string
    {
        if (static::$defaultLocale && static::$defaultLocale instanceof Closure) {
            return call_user_func(static::$defaultLocale);
        }

        return static::$defaultLocale ??= locale_get_default();
    }

    /**
     * Get the current locale.
     *
     * @return string The current locale.
     */
    public static function getLocale(): string
    {
        if (static::$locale && static::$locale instanceof Closure) {
            return call_user_func(static::$locale);
        }

        return static::$locale ?? static::getDefaultLocale();
    }

    /**
     * Get the paths.
     *
     * @return array The paths.
     */
    public static function getPaths(): array
    {
        return static::$paths;
    }

    /**
     * Remove a path.
     *
     * @param string $path The path to remove.
     * @return bool TRUE if the path was removed, otherwise FALSE.
     */
    public static function removePath(string $path): bool
    {
        $path = Path::resolve($path);

        foreach (static::$paths as $i => $otherPath) {
            if ($otherPath !== $path) {
                continue;
            }

            array_splice(static::$paths, $i, 1);

            return true;
        }

        return false;
    }

    /**
     * Set the default locale.
     *
     * @param Closure|string|null $locale The locale.
     */
    public static function setDefaultLocale(Closure|string|null $locale = null): void
    {
        static::$defaultLocale = $locale;
    }

    /**
     * Set the current locale.
     *
     * @param Closure|string|null $locale The locale, or a callback that returns the locale.
     */
    public static function setLocale(Closure|string|null $locale = null): void
    {
        static::$locale = $locale;
    }

    /**
     * Get a list of locales.
     *
     * @return array The locales.
     */
    private static function getLocales(): array
    {
        $locales = [];

        foreach ([static::getLocale(), static::getDefaultLocale()] as $locale) {
            $locale = strtolower($locale);
            $localeParts = explode('_', $locale);
            while ($localeParts !== []) {
                $newLocale = implode('_', $localeParts);

                if (in_array($newLocale, $locales)) {
                    break;
                }

                array_unshift($locales, $newLocale);
                array_pop($localeParts);
            }
        }

        return $locales;
    }

    /**
     * Load a language file.
     *
     * @param string $file The file.
     * @return array The language values.
     */
    private static function load(string $file): array
    {
        $file .= '.php';
        $locales = static::getLocales();

        $lang = [];
        foreach ($locales as $locale) {
            foreach (static::$paths as $path) {
                $filePath = Path::join($path, $locale, $file);

                if (!file_exists($filePath)) {
                    continue;
                }

                $data = require $filePath;
                $lang = array_replace_recursive($lang, $data);
            }
        }

        return $lang;
    }
}
