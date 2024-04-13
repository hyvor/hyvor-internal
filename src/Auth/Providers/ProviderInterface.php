<?php

namespace Hyvor\Internal\Auth\Providers;

use Hyvor\Internal\Auth\AuthUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Collection;

interface ProviderInterface
{

    public function check() : false|AuthUser;

    public function login(?string $redirect = null) : RedirectResponse|Redirector;
    public function signup(?string $redirect = null) : RedirectResponse|Redirector;
    public function logout(?string $redirect = null) : RedirectResponse|Redirector;

    /**
     * @param iterable<int> $ids
     * @return Collection<int, AuthUser>
     */
    public function fromIds(iterable $ids);
    public function fromId(int $id) : ?AuthUser;

    /**
     * @param iterable<string> $emails
     * @return Collection<string, AuthUser>
     */
    public function fromEmails(iterable $emails);
    public function fromEmail(string $email) : ?AuthUser;

    /**
     * @param iterable<string> $usernames
     * @return Collection<string, AuthUser>
     */
    public function fromUsernames(iterable $usernames);
    public function fromUsername(string $username) : ?AuthUser;

}
