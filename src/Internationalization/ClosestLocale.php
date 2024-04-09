<?php

namespace Hyvor\Internal\Internationalization;

class ClosestLocale
{

    public static function get(?string $locale) : string
    {

        $i18n = app(I18n::class);
        $locale ??= $i18n->defaultLocale;
        $locales = $i18n->getAvailableLocales();

        if (in_array($locale, $locales)) {
            return $locale;
        }

        $languagePart = explode('-', $locale)[0];

        foreach ($locales as $availableLocale) {
            if (explode('-', $availableLocale)[0] === $languagePart) {
                return $availableLocale;
            }
        }

        return $i18n->defaultLocale;

    }

}