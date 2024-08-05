<?php

namespace Hyvor\Internal\Tests\Unit\InternalApi;

it('does not allow missing component', function () {
    test()
        ->get(
            '/api/internal/internal-api-testing-test-route-from-middleware'
        )
        ->assertStatus(403)
        ->assertSee('Missing from component');
});

it('does not allow wrong from component', function () {
    test()
        ->withHeader('X-Internal-Api-From', 'talk')
        ->get(
            '/api/internal/internal-api-testing-test-route-from-middleware'
        )
        ->assertStatus(403)
        ->assertSee('Invalid from component');
});

it('allows correct from component', function () {
    test()
        ->withHeader('X-Internal-Api-From', 'core')
        ->get(
            '/api/internal/internal-api-testing-test-route-from-middleware'
        )
        ->assertStatus(200)
        ->assertSee('ok');
});