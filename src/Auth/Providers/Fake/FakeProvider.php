<?php

namespace Hyvor\Helper\Auth\Providers\Fake;

use Faker\Factory;
use Hyvor\Helper\Auth\AuthUser;
use Hyvor\Helper\Auth\Providers\ProviderInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Collection;

/**
 * @phpstan-import-type LoginUserArrayPartial from AuthUser
 */
class FakeProvider implements ProviderInterface
{

    /**
     * If $DATABASE is set, users will be searched (in fromX() methods) from this collection
     * Results will only be returned if the search is matched
     * If it is not set, all users will always be matched using fake data (for testing)
     * @var Collection<int, AuthUser>|null
     */
    public static ?Collection $DATABASE = null;

    public function check() : false|AuthUser
    {
        if ($this->getFakeUserId()) {
            return $this->fakeLoginUser();
        }
        return false;
    }

    public function login() : RedirectResponse|Redirector
    {
        return redirect();
    }
    public function signup() : RedirectResponse|Redirector
    {
        return redirect();
    }
    public function logout() : RedirectResponse|Redirector
    {
        return redirect();
    }

    /**
     * @param iterable<int> $ids
     * @return Collection<int, AuthUser>
     */
    public function fromIds(iterable $ids)
    {
        return $this->multiSearch('id', $ids);
    }
    public function fromId(int $id) : ?AuthUser
    {
        return $this->singleSearch('id', $id);
    }

    /**
     * @param iterable<string> $emails
     * @return Collection<int, AuthUser>
     */
    public function fromEmails(iterable $emails)
    {
        return $this->multiSearch('email', $emails);
    }
    public function fromEmail(string $email) : ?AuthUser
    {
        return $this->singleSearch('email', $email);
    }

    /**
     * @param iterable<string> $usernames
     * @return Collection<int, AuthUser>
     */
    public function fromUsernames(iterable $usernames)
    {
        return $this->multiSearch('username', $usernames);
    }
    public function fromUsername(string $username) : ?AuthUser
    {
        return $this->singleSearch('username', $username);
    }

    public static function getFakeUserId() : ?int
    {
        $id = config('hyvor-helper.auth.fake.user_id');
        if (is_int($id)) {
            return $id;
        }
        return null;
    }

    /**
     * @param LoginUserArrayPartial $fill
     */
    public static function fakeLoginUser(array $fill = []) : AuthUser
    {
        $faker = Factory::create();
        return AuthUser::fromArray(array_merge([
            'id' => self::getFakeUserId() ?? $faker->randomNumber(),
            'username' => $faker->name(),
            'name' => $faker->name(),
            'email' => $faker->email(),
            'picture_url' => 'https://picsum.photos/100/100'
        ], $fill));
    }

    /**
     * @param 'id' | 'username' | 'email' $key
     */
    private function singleSearch(string $key, string|int $value) : ?AuthUser
    {
        if (self::$DATABASE !== null) {
            return self::$DATABASE->firstWhere($key, $value);
        }

        // @phpstan-ignore-next-line
        return self::fakeLoginUser([$key => $value]);
    }

    /**
     * @param string $key
     * @param iterable<int|string> $values
     * @return Collection<int, AuthUser>
     */
    private function multiSearch(string $key, iterable $values) : Collection
    {
        if (self::$DATABASE !== null) {
            return self::$DATABASE->whereIn($key, $values)
                ->keyBy($key);
        }

        // @phpstan-ignore-next-line
        return collect($values)
            ->map(function ($value) use ($key) {
                // @phpstan-ignore-next-line
                return self::fakeLoginUser([$key => $value]);
            })
            ->keyBy($key);
    }

    /**
     * @param iterable<int, AuthUser|LoginUserArrayPartial> $users
     */
    public static function databaseSet(iterable $users = []) : void
    {
        self::$DATABASE = collect($users)
            ->map(function ($user) {
                if ($user instanceof AuthUser) {
                    return $user;
                }
                return self::fakeLoginUser($user);
            });
    }

    /**
     * @return Collection<int, AuthUser>|null
     */
    public static function databaseGet() : ?Collection
    {
        return self::$DATABASE;
    }

    public static function databaseClear() : void
    {
        self::$DATABASE = null;
    }

    /**
     * @param AuthUser|LoginUserArrayPartial $user
     */
    public static function databaseAdd($user) : void
    {
        if (self::$DATABASE === null) {
            self::$DATABASE = collect([]);
        }
        self::$DATABASE->push(
            $user instanceof AuthUser ? $user : self::fakeLoginUser($user)
        );
    }

}
