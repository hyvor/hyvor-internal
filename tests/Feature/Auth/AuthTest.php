<?php

namespace Hyvor\Internal\Tests\Feature;

use Hyvor\Internal\Auth\Auth;
use Illuminate\Http\RedirectResponse;

it('checks', function() {
    expect(Auth::check()->id)->toBe(1);

    config(['hyvor-helper.auth.fake.user_id' => 2]);
    expect(Auth::check()->id)->toBe(2);

    config(['hyvor-helper.auth.fake.user_id' => null]);
    expect(Auth::check())->toBeFalse();
});

it('redirects', function() {

    config(['hyvor-helper.auth.provider' => 'hyvor']);

    $login = Auth::login();
    expect($login)->toBeInstanceOf(RedirectResponse::class);
    expect($login->getTargetUrl())->toStartWith('https://hyvor.com/login?redirect=');

    $signup = Auth::signup();
    expect($signup)->toBeInstanceOf(RedirectResponse::class);
    expect($signup->getTargetUrl())->toStartWith('https://hyvor.com/signup?redirect=');

    $logout = Auth::logout();
    expect($logout)->toBeInstanceOf(RedirectResponse::class);
    expect($logout->getTargetUrl())->toStartWith('https://hyvor.com/logout?redirect=');

});