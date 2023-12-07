# hyvor-helper-laravel

This package provides the following features for HYVOR applications in Laravel:

- Unified Authentication for HYVOR, OpenID Connect, and Fake (for testing)

## Installation 

```bash
composer require hyvor/helper-laravel
```

## Providers

This library supports the following authentication providers:

- HYVOR
- OpenID Connect
- Fake (for testing)

### Configuration

The following environment variables are supported:

<table>
    <tr>
        <td>ENV</td>
        <td>Description</td>
        <td>Default</td>
    </tr>
    <tr>
        <td><code>AUTH_PROVIDER</code></td>
        <td>The authentication provider. One of <code>hyvor</code>, <code>oidc</code>, or <code>fake</code>.</td>
        <td><code>fake</code></td>
    </tr>
    <tr>
        <td colspan="3" style="text-align:center">
            <code>hyvor</code> provider
        </td>
    </tr>
    <tr>
        <td><code>AUTH_HYVOR_URL</code></td>
        <td>Public URL of the HYVOR instance. Users are redirected here for login and signup</td>
        <td><code>https://hyvor.com</code></td>
    </tr>
    <tr>
        <td><code>AUTH_HYVOR_PRIVATE_URL</code></td>
        <td>
            If the HYVOR instance is on a private network, set this to the private URL. Otherwise, the public URL will be used.
        </td>
        <td><code>AUTH_HYVOR_URL</code></td>
    </tr>
    <tr>
        <td><code>AUTH_HYVOR_API_KEY</code></td>
        <td>
            <strong>REQUIRED</strong>. The API key of the HYVOR instance. This is used to fetch user data.
        </td>
        <td><code>test-key</code></td>
    </tr>
</table>

### User Data

The `AuthUser` class is used to represent the user. It has the following properties:

- `int $id` - the user ID
- `string $name` - the user's name
- `string $email` - the user's email
- `?string $username` - the user's username (only HYVOR)
- `?string $picture_url` - the user's picture URL
- `?string $location` - the user's location
- `?string $bio` - the user's bio
- `?string $website_url` - the user's website URL
- `?string $sub` - the user's sub (only OpenID Connect)

#### Instantiation

```php
<?php
use Hyvor\Helper\Auth\AuthUser;

// new instance
new AuthUser(
    id: $id, 
    name: $name, 
    ...
);

// from array
AuthUser::fromArray([
    'id' => $id,
    'name' => $name
])
```

#### Fetch Data

Fetching user data should always be done using API calls rather than using SQL joins in application-level querie, even if OpenID Connect is used. This is because HYVOR user data is always stored in an external database.

Use the following methods to fetch data by user ID, email, or username:

```php
<?php
use Hyvor\Helper\Auth\AuthUser;

AuthUser::fromId($id);
AuthUser::fromIds($ids);

AuthUser::fromEmail($email);
AuthUser::fromEmails($emails);

AuthUser::fromUsername($username);
AuthUser::fromUsernames($usernames);
```

### Auth check

To check if the user is logged in:

```php
use Hyvor\Helper\Auth\Auth;

// AuthUser|null
$user = Auth::check();

if ($user) {
    // user is logged in
}
```

### Redirects

Use the following methods to get redirects to login, signup, and logout pages:

```php
use Hyvor\Helper\Auth\Auth;

$loginUrl = Auth::login();
$signupUrl = Auth::signup();
$logoutUrl = Auth::logout();
```
### Testing

In testing, the provider is always set to `fake`. The `FakeProvider` always generate dummy data for all requested ids, emails, and usernames. This is useful for testing. You may also set a database of users for the `FakeProvider` to return specific data for specific users as follows: 

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