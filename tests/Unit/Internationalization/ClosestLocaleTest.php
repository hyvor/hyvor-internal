<?php

namespace Hyvor\Internal\Tests\Unit\Internationalization;

use Hyvor\Internal\Internationalization\ClosestLocale;
use function Pest\testDirectory;

beforeEach(function() {
    config(['internal.i18n.folder' => __DIR__ . '/locales']);
});

it('gets the closest locale', function() {

    expect(ClosestLocale::get('en-US'))->toBe('en-US');
    expect(ClosestLocale::get('en-GB'))->toBe('en-US');
    expect(ClosestLocale::get('fr-FR'))->toBe('fr-FR');
    expect(ClosestLocale::get('fr'))->toBe('fr-FR');
    expect(ClosestLocale::get('es-ES'))->toBe('es');
    expect(ClosestLocale::get('es-MX'))->toBe('es');
    expect(ClosestLocale::get('pt'))->toBe('en-US');
    expect(ClosestLocale::get('invalid'))->toBe('en-US');

});