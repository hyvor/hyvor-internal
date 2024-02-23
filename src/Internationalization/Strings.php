<?php

namespace Hyvor\Helper\Internationalization;

use Hyvor\Helper\Internationalization\Exceptions\FormatException;
use Hyvor\Helper\Internationalization\Exceptions\InvalidStringKeyException;
use MessageFormatter;

class Strings
{

    public string $locale;

    public function __construct(string $locale)
    {
        $this->locale = ClosestLocale::get($locale);
    }

    /**
     * @param array<mixed> $params
     */
    public function get(string $key, array $params = []) : string
    {

        $i18n = app(I18n::class);

        $currentLocaleStrings = $i18n->getLocaleStrings($this->locale);

        $string = $this->getFromDotNotation($currentLocaleStrings, $key);

        if ($string === null) {

            $defaultLocaleStrings = $i18n->getDefaultLocaleStrings();
            $string = $this->getFromDotNotation($defaultLocaleStrings, $key);

            if ($string === null) {
                throw new InvalidStringKeyException('Invalid string key: ' . $key);
            }
        }

        $formatted = MessageFormatter::formatMessage($this->locale, $string, $params);

        if (!$formatted) {
            throw new FormatException('Unable to format message: ' . $string);
        }

        return $formatted;

    }

    /**
     * @param array<mixed> $arr
     */
    private function getFromDotNotation(array $arr, string $key) : ?string
    {

        $keys = explode('.', $key);
        $len = count($keys);

        foreach ($keys as $index => $key) {
            if ($index === $len - 1) {
                break; // don't processs the last element
            } else {
                if (!isset($arr[$key]) || !is_array($arr[$key])) {
                    return null;
                }
                $arr = $arr[$key];
            }
        }

        $val = $arr[$key] ?? null;
        return is_string($val) ? $val : null;

    }

}