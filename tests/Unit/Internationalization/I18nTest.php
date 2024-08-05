<?php

namespace Hyvor\Internal\Tests\Unit\Internationalization;

use Hyvor\Internal\Internationalization\I18n;
use RuntimeException;

beforeEach(function() {
    config(['internal.i18n.folder' => __DIR__ . '/locales']);
});

it('i18n works', function() {
    $i18n = app(I18n::class);
    expect($i18n->getAvailableLocales())->toBe(['en-US', 'es', 'fr-FR']);
    expect($i18n->getLocaleStrings('en-US')['name'])->toBe('HYVOR');
});

it('throws on cant read', function() {
    $i18n = app(I18n::class);
    $i18n->getLocaleStrings('es');
})->throws(RuntimeException::class, 'Could not read the locale file of es');

it('when locale not found', function() {
    $i18n = app(I18n::class);
    $i18n->getLocaleStrings('pb');
})->throws(RuntimeException::class, 'Locale pb not found');
