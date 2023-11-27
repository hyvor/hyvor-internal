<?php

namespace Hyvor\Helper\Login\Providers;

use Hyvor\Helper\Login\LoginUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Collection;

interface ProviderInterface
{

    public function check() : false|LoginUser;

    public function login() : RedirectResponse|Redirector;
    public function signup() : RedirectResponse|Redirector;
    public function logout() : RedirectResponse|Redirector;

    /**
     * @param iterable<string> $ids
     * @return Collection<int, LoginUser>
     */
    public function fromIds(iterable $ids);
    public function fromId(int $id) : ?LoginUser;

    /**
     * @param iterable<string> $emails
     * @return Collection<int, LoginUser>
     */
    public function fromEmails(iterable $emails);
    public function fromEmail(string $email) : ?LoginUser;

    /**
     * @param iterable<string> $usernames
     * @return Collection<int, LoginUser>
     */
    public function fromUsernames(iterable $usernames);
    public function fromUsername(string $username) : ?LoginUser;

}
