# hyvor-helper-laravel

This package provides the following features for HYVOR applications in Laravel:

- Unified Authentication for HYVOR, OpenID Connect, and Fake (for testing)

## Installation 

```bash
composer require hyvor/helper-laravel
```

## Authentication

## Providers

This library supports the following authentication providers:

- HYVOR
- OpenID Connect
- Fake (for testing)

### Configuration


### Auth check


### User Data

Usually, with HYVOR login, only the user ID is stored at the application level. Other data, such as name and picture URL, are fetched from the HYVOR API when needed. The `AuthUser` class has the following static methods to get user data:

- `fromId($id)`
- `fromIds($ids)`
- `fromEmail($email)`
- `fromEmails($emails)`
- `fromUsername($username)`
- `fromUsernames($usernames)`

The OpenID Connect provider stores all the user data at the application level. The same above methods can be used to get user data. The Fake provider always returns dummy data for all requested ids, emails, and usernames. A custom database can be set for the Fake provider as follows:

```php
use Hyvor\Helper\Auth\Providers\Fake\FakeProvider;

it('adds names to the email', function() {

    // set the database of users
    FakeProvider::databaseSet([
        [
            'id' => 1,
            'name' => 'John Doe',
        ]
    ]);
    
    // send email to user ID 1
    // then assert
    expect($email->body)->toContain('John Doe');

});
```

- `FakeProvider::databaseSet($database)` - sets the database (collection) of users.
- `FakeProvider::databaseAdd($user)` - adds a user to the database.
- `FakeProvider::databaseClear()` - clears the database. This should be called after each test case (tearDown).

When a database is set, the `FakeProvider` will return the user data from that database only. This is useful for testing the following scenarios:

- When a user is not found (set an empty database).
- When a user's specific details are needed (e.g. name, email, etc.) as in the above example.

In most other cases, you should be able to use the Fake provider without setting a database. Because it automatically generates dummy data for all users, you do not need to seed a database before each test case. However, note that user's data will be different for each test case.