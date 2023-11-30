<?php

namespace Hyvor\Helper\Auth\Providers;

use Hyvor\Helper\Auth\AuthUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Collection;

interface ProviderInterface
{

    public function check() : false|AuthUser;

    public function login() : RedirectResponse|Redirector;
    public function signup() : RedirectResponse|Redirector;
    public function logout() : RedirectResponse|Redirector;

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
