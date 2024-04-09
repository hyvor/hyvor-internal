<?php

namespace Hyvor\Internal\Auth\Providers\Hyvor;

use Hyvor\Internal\Auth\AuthUser;
use Hyvor\Internal\Auth\Providers\ProviderInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Collection;

/**
 * @phpstan-import-type AuthUserArray from AuthUser
 */
class HyvorProvider implements ProviderInterface
{

    public const HYVOR_SESSION_COOKIE_NAME = 'authsess';

    public function check(): false|AuthUser
    {

        $cookie = $_COOKIE[self::HYVOR_SESSION_COOKIE_NAME] ?? null;

        if (!$cookie) {
            return false;
        }

        $response = HyvorApiCaller::call('/check', [], [
            'Cookie' => self::HYVOR_SESSION_COOKIE_NAME . '=' . $cookie
        ]);

        if ($response->successful()) {
            /** @var AuthUserArray $data */
            $data = $response->json();
            return AuthUser::fromArray($data);
        }

        return false;

    }

    private function redirectTo(
        string $page,
        string $redirectPage = null
    ) : RedirectResponse|Redirector
    {
        $pos = strpos($page, '?');
        $placeholder = $pos === false ? '?' : '&';

        /** @var Request $request */
        $request = request();

        if ($redirectPage === null) {
            $redirectPage = $request->getPathInfo();
        }

        $redirectUrl = $redirectPage && str_starts_with($redirectPage, 'https://')
            ? $redirectPage
            : $request->getSchemeAndHttpHost() . $redirectPage;

        $redirect = $placeholder . 'redirect=' .
            urlencode($redirectUrl);

        return redirect(
            config('hyvor-internal.auth.hyvor.url') .
            '/' .
            $page .
            $redirect
        );
    }

    public function login(?string $redirect = null): RedirectResponse|Redirector
    {
        return $this->redirectTo('login', $redirect);
    }

    public function signup(?string $redirect = null): RedirectResponse|Redirector
    {
        return $this->redirectTo('signup', $redirect);
    }

    public function logout(?string $redirect = null): RedirectResponse|Redirector
    {
        return $this->redirectTo('logout', $redirect);
    }

    /**
     * @template T of int|string
     * @param iterable<T> $values
     * @return Collection<T, AuthUser>
     */
    private function callApiGetUsers(string $field, iterable $values)
    {
        $response = HyvorApiCaller::call('/users/from/' . $field, [
            $field => implode(',', (array) $values)
        ]);

        if ($response->successful()) {
            /** @var AuthUserArray[] $json */
            $json = $response->json();
            $users = collect($json);
            return $users->map(fn($user) => AuthUser::fromArray($user));
        }

        return collect();
    }

    public function fromIds(iterable $ids)
    {
        return $this->callApiGetUsers('ids', $ids);
    }

    public function fromId(int $id): ?AuthUser
    {
        return $this->fromIds([$id])->get($id);
    }

    public function fromEmails(iterable $emails)
    {
        return $this->callApiGetUsers('emails', $emails);
    }

    public function fromEmail(string $email): ?AuthUser
    {
        return $this->fromEmails([$email])->get($email);
    }

    public function fromUsernames(iterable $usernames)
    {
        return $this->callApiGetUsers('usernames', $usernames);
    }

    public function fromUsername(string $username): ?AuthUser
    {
        return $this->fromUsernames([$username])->get($username);
    }
}