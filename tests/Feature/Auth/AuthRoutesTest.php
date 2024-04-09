<?php

namespace Hyvor\Internal\Tests\Feature\Routes;

it('check when not logged in', function() {
    config([
        'hyvor-helper.auth.fake.user_id' => null
    ]);

    $this
        ->post('/api/auth/check')
        ->assertJsonPath('is_logged_in', false)
        ->assertJsonPath('user', null);
});

it('check when logged in', function() {
    config(['hyvor-helper.auth.fake.user_id' => 1]);

    $this
        ->post('/api/auth/check')
        ->assertJsonPath('is_logged_in', true)
        ->assertJsonPath('user.id', 1);
});

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