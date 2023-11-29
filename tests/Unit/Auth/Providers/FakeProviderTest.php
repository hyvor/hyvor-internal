<?php

namespace Unit\Auth\Providers;

use Hyvor\Helper\Auth\Providers\Fake\FakeProvider;

beforeEach(function () {
    $this->provider = new FakeProvider();
    FakeProvider::databaseClear();
});

// login check
it('check() based on user ID config', function () {
    expect($this->provider->check()->id)->toBe(1);

    config(['hyvor-helper.login.fake.user_id' => 2]);
    expect($this->provider->check()->id)->toBe(2);

    config(['hyvor-helper.login.fake.user_id' => null]);
    expect($this->provider->check())->toBeFalse();
});

it('database helper functions', function() {

    FakeProvider::databaseSet([
        ['id' => 1, 'name' => 'John'],
        ['id' => 2, 'name' => 'Jane'],
    ]);

    $db = FakeProvider::databaseGet();
    expect($db->count())->toBe(2);
    expect($db[0]->name)->toBe('John');
    expect($db[1]->name)->toBe('Jane');

    FakeProvider::databaseAdd(['id' => 3, 'name' => 'Jack']);
    expect($db->count())->toBe(3);
    expect($db[2]->name)->toBe('Jack');

    FakeProvider::databaseClear();
    expect(FakeProvider::databaseGet())->toBeNull();

    FakeProvider::databaseAdd(['id' => 3, 'name' => 'Jack']);
    $db = FakeProvider::databaseGet();
    expect($db->count())->toBe(1);
    expect($db[0]->name)->toBe('Jack');

});

it('fromId', function() {

    $id20 = $this->provider->fromId(20);
    expect($id20->name)->toBeString();
    expect($id20->id)->toBe(20);

    // with DB
    FakeProvider::databaseSet([
        ['id' => 1, 'name' => 'John'],
        ['id' => 2, 'name' => 'Jane'],
    ]);

    $id1 = $this->provider->fromId(1);
    expect($id1->name)->toBe('John');
    expect($id1->id)->toBe(1);

    $id3 = $this->provider->fromId(3);
    expect($id3)->toBeNull();

});

// from email
it('fromEmail', function() {

    $email20 = $this->provider->fromEmail('20@test.com');
    expect($email20->name)->toBeString();
    expect($email20->email)->toBe('20@test.com');

    // with DB
    FakeProvider::databaseSet([
        ['id' => 1, 'name' => 'John', 'email' => 'john@test.com'],
        ['id' => 2, 'name' => 'Jane', 'email' => 'jane@test.com']
    ]);

    $email1 = $this->provider->fromEmail('john@test.com');
    expect($email1->name)->toBe('John');
    expect($email1->email)->toBe('john@test.com');

    $email3 = $this->provider->fromEmail('supun@test.com');
    expect($email3)->toBeNull();

});

// from username
it('fromUsername', function() {
    $username20 = $this->provider->fromUsername('user20');
    expect($username20->name)->toBeString();
    expect($username20->username)->toBe('user20');

    // with DB
    FakeProvider::databaseSet([
        ['id' => 1, 'name' => 'John', 'username' => 'john'],
        ['id' => 2, 'name' => 'Jane', 'username' => 'jane']
    ]);

    $username1 = $this->provider->fromUsername('john');
    expect($username1->name)->toBe('John');
    expect($username1->username)->toBe('john');

    $username3 = $this->provider->fromUsername('supun');
    expect($username3)->toBeNull();
});

// from ids
it('fromIds', function() {
    $ids = $this->provider->fromIds([1, 2, 3]);
    expect($ids->count())->toBe(3);
    expect($ids[1]->id)->toBe(1);
    expect($ids[2]->id)->toBe(2);
    expect($ids[3]->id)->toBe(3);

    // with DB
    FakeProvider::databaseSet([
        ['id' => 1, 'name' => 'John', 'username' => 'john'],
        ['id' => 2, 'name' => 'Jane', 'username' => 'jane']
    ]);

    $ids = $this->provider->fromIds([1, 2, 3]);
    expect($ids->count())->toBe(2);
    expect($ids[1]->id)->toBe(1);
    expect($ids[2]->id)->toBe(2);
});

// from usernames
it('fromUsernames', function() {
    $usernames = $this->provider->fromUsernames(['user1', 'user2', 'user3']);
    expect($usernames->count())->toBe(3);
    expect($usernames['user1']->username)->toBe('user1');
    expect($usernames['user2']->username)->toBe('user2');
    expect($usernames['user3']->username)->toBe('user3');

    // with DB
    FakeProvider::databaseSet([
        ['id' => 1, 'name' => 'John', 'username' => 'john'],
        ['id' => 2, 'name' => 'Jane', 'username' => 'jane']
    ]);

    $usernames = $this->provider->fromUsernames(['john', 'jane', 'supun']);
    expect($usernames->count())->toBe(2);
    expect($usernames['john']->username)->toBe('john');
    expect($usernames['jane']->username)->toBe('jane');
});

// from emails
it('fromEmails', function() {
    $emails = $this->provider->fromEmails(['user1@test.com', 'user2@test.com']);
    expect($emails->count())->toBe(2);
    expect($emails['user1@test.com']->email)->toBe('user1@test.com');
    expect($emails['user2@test.com']->email)->toBe('user2@test.com');

    // with DB
    FakeProvider::databaseSet([
        ['id' => 1, 'name' => 'John', 'email' => 'john@test.com'],
        ['id' => 2, 'name' => 'Jane', 'email' => 'jane@test.com']
    ]);

    $emails = $this->provider->fromEmails(['john@test.com', 'jane@test.com', 'roger@test.com']);
    expect($emails->count())->toBe(2);
    expect($emails['john@test.com']->email)->toBe('john@test.com');
    expect($emails['jane@test.com']->email)->toBe('jane@test.com');

});