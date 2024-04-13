<?php

namespace Hyvor\Internal\Auth\Providers\Oidc;

use Hyvor\Internal\Auth\AuthUser;
use Hyvor\Internal\Auth\Providers\ProviderInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class OidcProvider implements ProviderInterface
{


    public function check(): false|AuthUser
    {
        return false;
    }

    public function login(?string $redirect = null): RedirectResponse|Redirector
    {
        return redirect('');
    }

    public function signup(?string $redirect = null): RedirectResponse|Redirector
    {
        return redirect('');
    }

    public function logout(?string $redirect = null): RedirectResponse|Redirector
    {
        return redirect('');
    }

    public function fromIds(iterable $ids)
    {
        return collect([]);
    }

    public function fromId(int $id): ?AuthUser
    {
        return null;
    }

    public function fromEmails(iterable $emails)
    {
        return collect([]);
    }

    public function fromEmail(string $email): ?AuthUser
    {
        return null;
    }

    public function fromUsernames(iterable $usernames)
    {
        return collect([]);
    }

    public function fromUsername(string $username): ?AuthUser
    {
        return null;
    }
}