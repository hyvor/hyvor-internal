<?php

namespace Hyvor\Helper\Auth\Providers\Oidc;

use Hyvor\Helper\Auth\AuthUser;
use Hyvor\Helper\Auth\Providers\ProviderInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class OidcProvider implements ProviderInterface
{


    public function check(): false|AuthUser
    {
        // TODO: Implement check() method.
    }

    public function login(): RedirectResponse|Redirector
    {
        // TODO: Implement login() method.
    }

    public function signup(): RedirectResponse|Redirector
    {
        // TODO: Implement signup() method.
    }

    public function logout(): RedirectResponse|Redirector
    {
        // TODO: Implement logout() method.
    }

    public function fromIds(iterable $ids)
    {
        // TODO: Implement fromIds() method.
    }

    public function fromId(int $id): ?AuthUser
    {
        // TODO: Implement fromId() method.
    }

    public function fromEmails(iterable $emails)
    {
        // TODO: Implement fromEmails() method.
    }

    public function fromEmail(string $email): ?AuthUser
    {
        // TODO: Implement fromEmail() method.
    }

    public function fromUsernames(iterable $usernames)
    {
        // TODO: Implement fromUsernames() method.
    }

    public function fromUsername(string $username): ?AuthUser
    {
        // TODO: Implement fromUsername() method.
    }
}