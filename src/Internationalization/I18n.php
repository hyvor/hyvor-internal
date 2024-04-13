<?php

namespace Hyvor\Internal\Internationalization;

use RuntimeException;


class I18n
{

    public string $folder;

    /** @var string[]  */
    public array $availableLocales;

    public string $defaultLocale;


    /** @var array<string, array<mixed>> */
    public array $stringsCache = [];

    public function __construct()
    {

        $config = config('hyvor-internal.i18n');

        $this->folder = $config['folder'] ?? './locales';
        $this->availableLocales = $this->setAvailableLocales();

        $this->defaultLocale = (string) ($config['default'] ?? 'en-US');
        $this->stringsCache[$this->defaultLocale] = $this->getLocaleStrings($this->defaultLocale);

    }

    /**
     * @return array<string>
     */
    private function setAvailableLocales() : array
    {

        $locales = [];
        $files = scandir($this->folder);

        if ($files === false) {
            throw new RuntimeException('Could not read the locales folder');
        }

        foreach ($files as $file) {
            if (is_file($this->folder . '/' . $file)) {
                $locales[] = pathinfo($file, PATHINFO_FILENAME);
            }
        }
        return $locales;

    }

    /**
     * @return string[]
     */
    public function getAvailableLocales() : array
    {
        return $this->availableLocales;
    }

    /**
     * @return array<mixed>
     */
    public function getLocaleStrings(string $locale) : array
    {

        if (isset($this->stringsCache[$locale])) {
            return $this->stringsCache[$locale];
        }

        $file = $this->folder . '/' . $locale . '.json';

        if (!in_array($locale, $this->availableLocales)) {
            throw new RuntimeException("Locale $locale not found");
        }

        if (!file_exists($file)) {
            throw new RuntimeException('Locale file not found');
        }

        $json = file_get_contents($file);

        if (!$json) {
            throw new RuntimeException('Could not read the locale file of ' . $locale);
        }

        return (array) json_decode($json, true);

    }

    /**
     * @return mixed[]
     */
    public function getDefaultLocaleStrings() : array
    {
        return $this->stringsCache[$this->defaultLocale];
    }

}