<?php

namespace Hyvor\Helper\Tests\Feature\Routes;

it('redirects', function() {

    config(['hyvor-helper.auth.provider' => 'hyvor']);

    $this
        ->get('/api/auth/login')
        ->assertRedirectContains('https://hyvor.com/login?redirect=');

    $this
        ->get('/api/auth/signup')
        ->assertRedirectContains('https://hyvor.com/signup?redirect=');

    $this
        ->get('/api/auth/logout')
        ->assertRedirectContains('https://hyvor.com/logout?redirect=');

});

it('redirects with redirect', function() {

    config(['hyvor-helper.auth.provider' => 'hyvor']);

    $redirectUrl = urlencode('https://example.com');

    $this
        ->get('/api/auth/login?redirect=' . $redirectUrl)
        ->assertRedirectContains('https://hyvor.com/login?redirect=' . $redirectUrl);

    $this
        ->get('/api/auth/signup?redirect=' . $redirectUrl)
        ->assertRedirectContains('https://hyvor.com/signup?redirect=' . $redirectUrl);

    $this
        ->get('/api/auth/logout?redirect=' . $redirectUrl)
        ->assertRedirectContains('https://hyvor.com/logout?redirect=' . $redirectUrl);

});