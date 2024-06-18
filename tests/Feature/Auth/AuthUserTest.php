<?php

namespace Hyvor\Internal\Tests\Feature;

use Hyvor\Internal\Auth\AuthUser;
use Illuminate\Support\Collection;

it('is created from an array', function() {

    $user = AuthUser::fromArray([
        'id' => 1,
        'name' => 'John Doe',
        'username' => 'johndoe',
        'email' => 'john@hyvor.com',
        'picture_url' => 'https://hyvor.com/john.jpg',
    ]);

    expect($user->id)->toBe(1);
    expect($user->name)->toBe('John Doe');
    expect($user->username)->toBe('johndoe');
    expect($user->email)->toBe('john@hyvor.com');
    expect($user->picture_url)->toBe('https://hyvor.com/john.jpg');
    expect($user->location)->toBeNull();
    expect($user->bio)->toBeNull();
    expect($user->website_url)->toBeNull();
    expect($user->email_relay)->toBeNull();

});

// tested using the fake provider
it('from ids', function() {

    $users = AuthUser::fromIds([1,2]);

    expect($users)->toBeInstanceOf(Collection::class);
    expect($users->count())->toBe(2);
    expect($users->first())->toBeInstanceOf(AuthUser::class);
    expect($users->first()->id)->toBe(1);
    expect($users->last()->id)->toBe(2);

    $user = AuthUser::fromId(3);

    expect($user)->toBeInstanceOf(AuthUser::class);
    expect($user->id)->toBe(3);

});

it('from usernames', function() {

    $users = AuthUser::fromUsernames(['johndoe', 'janedoe']);

    expect($users)->toBeInstanceOf(Collection::class);
    expect($users->count())->toBe(2);
    expect($users->first())->toBeInstanceOf(AuthUser::class);
    expect($users->first()->username)->toBe('johndoe');
    expect($users->last()->username)->toBe('janedoe');

    $user = AuthUser::fromUsername('jimdoe');

    expect($user)->toBeInstanceOf(AuthUser::class);
    expect($user->username)->toBe('jimdoe');

});

it('from emails', function() {

    $users = AuthUser::fromEmails(['johndoe@hyvor.com', 'janedoe@hyvor.com']);

    expect($users)->toBeInstanceOf(Collection::class);
    expect($users->count())->toBe(2);
    expect($users->first())->toBeInstanceOf(AuthUser::class);
    expect($users->first()->email)->toBe('johndoe@hyvor.com');
    expect($users->last()->email)->toBe('janedoe@hyvor.com');

    $user = AuthUser::fromEmail('jimdoe@hyvor.com');

    expect($user)->toBeInstanceOf(AuthUser::class);
    expect($user->email)->toBe('jimdoe@hyvor.com');

});