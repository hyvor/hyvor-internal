<?php

namespace Hyvor\Internal\Tests\Unit\Auth\Providers;

use Hyvor\Internal\Auth\AuthUser;
use Hyvor\Internal\Auth\Providers\Hyvor\HyvorProvider;
use Illuminate\Http\Client\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

beforeEach(function() {
    $this->provider = new HyvorProvider();
});

it('check when no cookie set', function() {
    $_COOKIE = [];
    expect($this->provider->check())->toBeFalse();
});

it('check when cookie is set', function() {

    $_COOKIE = [
        HyvorProvider::HYVOR_SESSION_COOKIE_NAME => 'test-cookie'
    ];

    Http::fake([
        'https://hyvor.com/api/auth/check' => Http::response([
            'id' => 1,
            'name' => 'test',
            'username' => 'test',
            'email' => 'test@test.com'
        ])
    ]);

    $user = $this->provider->check();

    expect($user)->toBeInstanceOf(AuthUser::class);
    expect($user->id)->toBe(1);
    expect($user->name)->toBe('test');
    expect($user->username)->toBe('test');
    expect($user->email)->toBe('test@test.com');

    Http::assertSent(function (Request $request) {
        expect(
            $request->hasHeader('Cookie', HyvorProvider::HYVOR_SESSION_COOKIE_NAME . '=test-cookie')
        )->toBeTrue();
        expect($request['key'])->toBe('test-key');
        return true;
    });

});

it('returns false when check fails', function() {
    $_COOKIE = [
        HyvorProvider::HYVOR_SESSION_COOKIE_NAME => 'test'
    ];
    Http::fake([
        'https://hyvor.com/api/auth/check' => Http::response([], 422)
    ]);
    expect($this->provider->check())->toBeFalse();
});


it('redirects', function() {

    $login = $this->provider->login();
    expect($login)->toBeInstanceOf(RedirectResponse::class);
    expect($login->getTargetUrl())->toStartWith('https://hyvor.com/login?redirect=');

    $signup = $this->provider->signup();
    expect($signup)->toBeInstanceOf(RedirectResponse::class);
    expect($signup->getTargetUrl())->toStartWith('https://hyvor.com/signup?redirect=');

    $logout = $this->provider->logout();
    expect($logout)->toBeInstanceOf(RedirectResponse::class);
    expect($logout->getTargetUrl())->toStartWith('https://hyvor.com/logout?redirect=');


    // page
    $login = $this->provider->login('/exit');
    expect($login)->toBeInstanceOf(RedirectResponse::class);
    expect($login->getTargetUrl())->toStartWith('https://hyvor.com/login?redirect=');
    expect($login->getTargetUrl())->toContain('redirect=http%3A%2F%2Flocalhost%2Fexit');

    // full URL
    $login = $this->provider->login('https://example.com/exit');
    expect($login)->toBeInstanceOf(RedirectResponse::class);
    expect($login->getTargetUrl())->toStartWith('https://hyvor.com/login?redirect=');
    expect($login->getTargetUrl())->toContain('redirect=https%3A%2F%2Fexample.com%2Fexit');

});

it('from ids', function() {

    Http::fake([
        'https://hyvor.com/api/auth/users/from/ids' => Http::response([
            1 => [
                'id' => 1,
                'name' => 'test',
                'username' => 'test',
                'email' => 'test@hyvor.com'
            ],
            2 => [
                'id' => 2,
                'name' => 'test2',
                'username' => 'test2',
                'email' => 'test2@hyvor.com'
            ]
        ])
    ]);

    $users = $this->provider->fromIds([1, 2]);

    expect($users)->toBeInstanceOf(Collection::class);
    expect($users->count())->toBe(2);

    expect($users[1])->toBeInstanceOf(AuthUser::class);
    expect($users[1]->id)->toBe(1);
    expect($users[1]->name)->toBe('test');
    expect($users[1]->username)->toBe('test');
    expect($users[1]->email)->toBe('test@hyvor.com');

    expect($users[2])->toBeInstanceOf(AuthUser::class);
    expect($users[2]->id)->toBe(2);
    expect($users[2]->name)->toBe('test2');
    expect($users[2]->username)->toBe('test2');
    expect($users[2]->email)->toBe('test2@hyvor.com');

    Http::assertSent(function (Request $request) {
        expect($request['key'])->toBe('test-key');
        expect($request['ids'])->toBe('1,2');
        return true;
    });

});

it('from id', function() {

    Http::fake([
        'https://hyvor.com/api/auth/users/from/ids' => Http::response([
            1 => [
                'id' => 1,
                'name' => 'test',
                'username' => 'test',
                'email' => 'test@hyvor.com',
                'picture_url' => 'https://hyvor.com/avatar.png'
            ]
        ])
    ]);

    $user = $this->provider->fromId(1);

    expect($user)->toBeInstanceOf(AuthUser::class);
    expect($user->id)->toBe(1);
    expect($user->name)->toBe('test');
    expect($user->username)->toBe('test');
    expect($user->email)->toBe('test@hyvor.com');
    expect($user->picture_url)->toBe('https://hyvor.com/avatar.png');

    Http::assertSent(function (Request $request) {
        expect($request['key'])->toBe('test-key');
        expect($request['ids'])->toBe('1');
        return true;
    });

});

it('from id - not found', function() {

    Http::fake([
        'https://hyvor.com/api/auth/users/from/ids' => Http::response([])
    ]);
    $user = $this->provider->fromId(1);
    expect($user)->toBeNull();

});

it('from usernames', function() {

    Http::fake([
        'https://hyvor.com/api/auth/users/from/usernames' => Http::response([
            'test' => [
                'id' => 1,
                'name' => 'test',
                'username' => 'test',
                'email' => 'test@hyvor.com',
            ],
            'test2' => [
                'id' => 2,
                'name' => 'test2',
                'username' => 'test2',
                'email' => 'test2@hyvor.com',
            ]
        ])
    ]);

    $users = $this->provider->fromUsernames(['test', 'test2']);

    expect($users)->toBeInstanceOf(Collection::class);
    expect($users->count())->toBe(2);

    expect($users['test'])->toBeInstanceOf(AuthUser::class);
    expect($users['test']->id)->toBe(1);
    expect($users['test']->name)->toBe('test');
    expect($users['test']->username)->toBe('test');
    expect($users['test']->email)->toBe('test@hyvor.com');

    expect($users['test2'])->toBeInstanceOf(AuthUser::class);
    expect($users['test2']->id)->toBe(2);
    expect($users['test2']->name)->toBe('test2');
    expect($users['test2']->username)->toBe('test2');
    expect($users['test2']->email)->toBe('test2@hyvor.com');

    Http::assertSent(function (Request $request) {
        expect($request['key'])->toBe('test-key');
        expect($request['usernames'])->toBe('test,test2');
        return true;
    });

});

it('from username', function() {

    Http::fake([
        'https://hyvor.com/api/auth/users/from/usernames' => Http::response([
            'test' => [
                'id' => 1,
                'name' => 'test',
                'username' => 'test',
                'email' => 'test@hyvor.com',
            ]
        ])
    ]);

    $user = $this->provider->fromUsername('test');

    expect($user)->toBeInstanceOf(AuthUser::class);
    expect($user->id)->toBe(1);
    expect($user->name)->toBe('test');
    expect($user->username)->toBe('test');
    expect($user->email)->toBe('test@hyvor.com');

    Http::assertSent(function (Request $request) {
        expect($request['key'])->toBe('test-key');
        expect($request['usernames'])->toBe('test');
        return true;
    });

});

it('from username - not found', function() {

    Http::fake([
        'https://hyvor.com/api/auth/users/from/usernames' => Http::response([])
    ]);
    $user = $this->provider->fromUsername('test');
    expect($user)->toBeNull();

});

it('from emails', function() {

    Http::fake([
        'https://hyvor.com/api/auth/users/from/emails' => Http::response([
            'test@hyvor.com' => [
                'id' => 1,
                'name' => 'test',
                'username' => 'test',
                'email' => 'test@hyvor.com',
            ],
            'test2@hyvor.com' => [
                'id' => 2,
                'name' => 'test2',
                'username' => 'test2',
                'email' => 'test2@hyvor.com',
            ]
        ])
    ]);

    $users = $this->provider->fromEmails(['test@hyvor.com', 'test2@hyvor.com']);

    expect($users)->toBeInstanceOf(Collection::class);
    expect($users->count())->toBe(2);

    expect($users['test@hyvor.com'])->toBeInstanceOf(AuthUser::class);
    expect($users['test@hyvor.com']->id)->toBe(1);
    expect($users['test@hyvor.com']->name)->toBe('test');
    expect($users['test@hyvor.com']->username)->toBe('test');
    expect($users['test@hyvor.com']->email)->toBe('test@hyvor.com');

    expect($users['test2@hyvor.com'])->toBeInstanceOf(AuthUser::class);
    expect($users['test2@hyvor.com']->id)->toBe(2);
    expect($users['test2@hyvor.com']->name)->toBe('test2');
    expect($users['test2@hyvor.com']->username)->toBe('test2');
    expect($users['test2@hyvor.com']->email)->toBe('test2@hyvor.com');

    Http::assertSent(function (Request $request) {
        expect($request['key'])->toBe('test-key');
        expect($request['emails'])->toBe('test@hyvor.com,test2@hyvor.com');
        return true;
    });

});


it('from email', function() {

    Http::fake([
        'https://hyvor.com/api/auth/users/from/emails' => Http::response([
            'test@hyvor.com' => [
                'id' => 1,
                'name' => 'test',
                'username' => 'test',
                'email' => 'test@hyvor.com',
            ],
        ])
    ]);

    $user = $this->provider->fromEmail('test@hyvor.com');

    expect($user)->toBeInstanceOf(AuthUser::class);
    expect($user->id)->toBe(1);
    expect($user->name)->toBe('test');
    expect($user->username)->toBe('test');
    expect($user->email)->toBe('test@hyvor.com');

    Http::assertSent(function (Request $request) {
        expect($request['key'])->toBe('test-key');
        expect($request['emails'])->toBe('test@hyvor.com');
        return true;
    });

});

it('from email - not found', function() {

    Http::fake([
        'https://hyvor.com/api/auth/users/from/emails' => Http::response([])
    ]);
    $user = $this->provider->fromEmail('test@hyvor.com');
    expect($user)->toBeNull();

});