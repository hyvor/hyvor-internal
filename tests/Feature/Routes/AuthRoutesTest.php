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