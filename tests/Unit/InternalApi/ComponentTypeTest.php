<?php

namespace Hyvor\Internal\Tests\Unit\InternalApi;

use Hyvor\Internal\InternalApi\ComponentType;

it('gets core url', function() {
    config(['app.url' => 'https://hyvor.com']);
    expect(ComponentType::CORE->getCoreUrl())->toBe('https://hyvor.com');

    config(['app.url' => 'https://talk.hyvor.com']);
    expect(ComponentType::TALK->getCoreUrl())->toBe('https://hyvor.com');

    // externl
    config(['app.url' => 'https://hyvor.mycompany.com']);
    expect(ComponentType::CORE->getCoreUrl())->toBe('https://hyvor.mycompany.com');

    // external product
    config(['app.url' => 'https://talk.hyvor.mycompany.com']);
    expect(ComponentType::TALK->getCoreUrl())->toBe('https://hyvor.mycompany.com');

});

it('gets the URL', function() {

    // core
    config(['app.url' => 'https://hyvor.com']);
    expect(ComponentType::CORE->getUrlOf(ComponentType::TALK))->toBe('https://talk.hyvor.com');
    expect(ComponentType::CORE->getUrlOf(ComponentType::CORE))->toBe('https://hyvor.com');

    // product
    config(['app.url' => 'https://talk.hyvor.com']);
    expect(ComponentType::TALK->getUrlOf(ComponentType::CORE))->toBe('https://hyvor.com');
    expect(ComponentType::TALK->getUrlOf(ComponentType::BLOGS))->toBe('https://blogs.hyvor.com');

    // other subdomain
    config(['app.url' => 'https://blogs.hyvor.mycompany.com']);
    expect(ComponentType::BLOGS->getUrlOf(ComponentType::CORE))->toBe('https://hyvor.mycompany.com');
    expect(ComponentType::BLOGS->getUrlOf(ComponentType::TALK))->toBe('https://talk.hyvor.mycompany.com');

});
