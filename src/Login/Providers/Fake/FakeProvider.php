<?php

namespace Hyvor\Helper\Login\Providers\Fake;

use Hyvor\Helper\Login\LoginUser;
use Hyvor\Helper\Login\Providers\ProviderInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Faker\Factory;
use Illuminate\Support\Collection;

class FakeProvider implements ProviderInterface
{


    /**
     * If $FAKE is set, users will be searched (in fromX() methods) from this collection
     * Results will only be returned if the search is matched
     * If it is not set, all users will be matched always using fake data (for testing)
     * @var Collection<int, LoginUser>|null
     */
    public static ?Collection $FAKE = null;


    public function check() : false|LoginUser
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
     * @param iterable<string> $ids
     * @return Collection<int, LoginUser>
     */
    public function fromIds(iterable $ids)
    {
        return collect([]);
    }
    public function fromId(int $id) : ?LoginUser
    {
        return $this->singleSearch('id', $id);
    }

    /**
     * @param iterable<string> $emails
     * @return Collection<int, LoginUser>
     */
    public function fromEmails(iterable $emails)
    {
        return collect([]);
    }
    public function fromEmail(string $email) : ?LoginUser
    {
        return $this->singleSearch('email', $email);
    }

    /**
     * @param iterable<string> $usernames
     * @return Collection<int, LoginUser>
     */
    public function fromUsernames(iterable $usernames)
    {
        return collect([]);
    }
    public function fromUsername(string $username) : ?LoginUser
    {
        return $this->singleSearch('username', $username);
    }

    

    private function getFakeUserId() : ?int
    {
        $id = config('hyvor-helper.login.fake.user_id');
        if (is_int($id)) {
            return $id;
        }
        return null;
    }

    /**
     * @param array<string, mixed> $fill
     */
    private function fakeLoginUser(array $fill = []) : LoginUser
    {
        $faker = Factory::create();
        // @phpstan-ignore-next-line
        return LoginUser::fromArray(array_merge([
            'id' => $this->getFakeUserId() ?? $faker->randomNumber(),
            'username' => $faker->name(),
            'name' => $faker->name(),
            'email' => $faker->email(),
            'picture_url' => 'https://picsum.photos/100/100'
        ], $fill));
    }

    private function singleSearch(string $key, string|int $value) : ?LoginUser
    {
        if ($this->getFakeUserId()) {
            return $this->fakeLoginUser([
                $key => $value,
            ]);
        }
        return null;
    }

}
