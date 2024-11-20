<?php
declare(strict_types=1);

namespace Fyre\Lang;

use Fyre\Config\Config;
use Fyre\Utility\Arr;
use Fyre\Utility\Path;
use MessageFormatter;

use function array_pop;
use function array_replace_recursive;
use function array_splice;
use function array_unique;
use function array_unshift;
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
class Lang
{
    protected string|null $defaultLocale = null;

    protected array $lang = [];

    protected string|null $locale = null;

    protected array $paths = [];

    /**
     * New Lang constructor.
     *
     * @param Config $config The Config.
     */
    public function __construct(Config $config)
    {
        $this->defaultLocale = $config->get('App.defaultLocale');
    }

    /**
     * Add a language path.
     *
     * @param string $path The path to add.
     * @return static The Lang.
     */
    public function addPath(string $path, bool $prepend = false): static
    {
        $path = Path::resolve($path);

        if (!in_array($path, $this->paths)) {
            if ($prepend) {
                array_unshift($this->paths, $path);
            } else {
                $this->paths[] = $path;
            }
        }

        return $this;
    }

    /**
     * Clear the language data.
     */
    public function clear(): void
    {
        $this->paths = [];
        $this->lang = [];
    }

    /**
     * Get a language value.
     *
     * @param string $key The language key.
     * @param array $data The data to insert.
     * @return array|string|null The formatted language string.
     */
    public function get(string $key, array $data = []): array|string|null
    {
        $file = strtok($key, '.');

        $this->lang[$file] ??= $this->load($file);

        $line = Arr::getDot($this->lang, $key);

        if (!$line || $data === [] || is_array($line)) {
            return $line;
        }

        return MessageFormatter::formatMessage($this->getLocale(), $line, $data);
    }

    /**
     * Get the default locale.
     *
     * @return string The default locale.
     */
    public function getDefaultLocale(): string
    {
        return $this->defaultLocale ??= locale_get_default();
    }

    /**
     * Get the current locale.
     *
     * @return string The current locale.
     */
    public function getLocale(): string
    {
        return $this->locale ?? $this->getDefaultLocale();
    }

    /**
     * Get the paths.
     *
     * @return array The paths.
     */
    public function getPaths(): array
    {
        return $this->paths;
    }

    /**
     * Remove a path.
     *
     * @param string $path The path to remove.
     * @return static The Lang.
     */
    public function removePath(string $path): static
    {
        $path = Path::resolve($path);

        foreach ($this->paths as $i => $otherPath) {
            if ($otherPath !== $path) {
                continue;
            }

            array_splice($this->paths, $i, 1);
            break;
        }

        return $this;
    }

    /**
     * Set the default locale.
     *
     * @param string|null $locale The locale.
     * @return static The Lang.
     */
    public function setDefaultLocale(string|null $locale = null): static
    {
        $this->defaultLocale = $locale;

        $this->lang = [];

        return $this;
    }

    /**
     * Set the current locale.
     *
     * @param string|null $locale The locale, or a callback that returns the locale.
     * @return static The Lang.
     */
    public function setLocale(string|null $locale = null): static
    {
        $this->locale = $locale;

        $this->lang = [];

        return $this;
    }

    /**
     * Get a list of locales.
     *
     * @return array The locales.
     */
    protected function getLocales(): array
    {
        $testLocales = array_unique([$this->getLocale(), $this->getDefaultLocale()]);

        $locales = [];

        foreach ($testLocales as $locale) {
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
    protected function load(string $file): array
    {
        $file .= '.php';
        $locales = $this->getLocales();

        $lang = [];
        foreach ($locales as $locale) {
            foreach ($this->paths as $path) {
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
