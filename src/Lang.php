<?php
declare(strict_types=1);

namespace Fyre\Lang;

use
    Fyre\Utility\Path,
    Locale,
    MessageFormatter;

use function
    array_key_exists,
    array_pop,
    array_unshift,
    explode,
    file_exists,
    implode,
    in_array,
    is_array,
    rtrim,
    strtok,
    strtolower;

/**
 * Lang
 */
abstract class Lang
{

    private static array $paths = [];

    private static array $lang = [];

    private static string|null $defaultLocale = null;

    private static string|null $locale = null;

    /**
     * Add a language path.
     * @param string $path The path to add.
     * @param bool $prepend Whether to prepend the path.
     */
    public static function addPath(string $path, bool $prepend = false): void
    {
        $path = Path::resolve($path);

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
     * @param string $key The language key.
     * @param array $data The data to insert.
     * @return string|array|null The formatted language string.
     */
    public static function get(string $key, array $data = []): string|array|null
    {
        $file = strtok($key, '.');

        static::$lang[$file] ??= static::load($file);

        $line = static::getDot(static::$lang, $key);

        if (!$line || $data === [] || is_array($line)) {
            return $line;
        }

        return MessageFormatter::formatMessage(static::getLocale(), $line, $data);
    }

    /**
     * Get the default locale.
     * @return string The default locale.
     */
    public static function getDefaultLocale(): string
    {
        return static::$defaultLocale ?? Locale::getDefault();
    }

    /**
     * Get the current locale.
     * @return string The current locale.
     */
    public static function getLocale(): string
    {
        return static::$locale ?? static::getDefaultLocale();
    }

    /**
     * Set the default locale.
     * @param string|null $locale The locale.
     */
    public static function setDefaultLocale(string|null $locale = null): void
    {
        static::$defaultLocale = $locale;
    }

    /**
     * Set the current locale.
     * @param string|null $locale The locale.
     */
    public static function setLocale(string|null $locale = null): void
    {
        static::$locale = $locale;
    }

    /**
     * Retrieve a value using "dot" notation.
     * @param array $array The input array.
     * @param string $key The key.
     * @return mixed The value.
     */
    private static function getDot(array $array, string $key): mixed
    {
        $result = $array;

        foreach (explode('.', $key) AS $key) {
            if (!is_array($result) || !array_key_exists($key, $result)) {
                return null;
            }

            $result = $result[$key];
        }

        return $result;
    }

    /**
     * Get a list of locales.
     * @return array The locales.
     */
    private static function getLocales(): array
    {
        $locales = [];

        foreach ([static::getLocale(), static::getDefaultLocale()] AS $locale) {
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
     * @param string $file The file.
     * @return array The language values.
     */
    private static function load(string $file): array
    {
        $file .= '.php';
        $locales = static::getLocales();

        $lang = [];
        foreach ($locales AS $locale) {
            foreach (static::$paths AS $path) {
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
