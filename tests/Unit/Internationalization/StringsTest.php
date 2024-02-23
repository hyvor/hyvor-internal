<?php

namespace Hyvor\Helper\Tests\Unit\Internationalization;

use Hyvor\Helper\Internationalization\Strings;

beforeEach(function() {
    config(['hyvor-helper.i18n.folder' => __DIR__ . '/locales']);
});

it('gets strings default', function() {

    $locale = new Strings('en-US');

    expect($locale->get('name'))->toBe('HYVOR');
    expect($locale->get('greet', ['name' => 'you']))->toBe('Hello, you!');
    expect($locale->get('signup.cta'))->toBe('Sign up now');

    // closest locale
    $locale = new Strings('en');
    expect($locale->get('name'))->toBe('HYVOR');

});

it('gets strings non-default', function() {

    $locale = new Strings('fr-FR');
    expect($locale->get('greet', ['name' => 'you']))->toBe('Bonjour, you!');

    // fallback
    expect($locale->get('name'))->toBe('HYVOR');

});

it('missing locale', function() {

    $locale = new Strings('si');
    expect($locale->get('name'))->toBe('HYVOR');

});