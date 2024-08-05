<?php declare(strict_types=1);

namespace Hyvor\Internal\Auth\Providers;

use Hyvor\Internal\Auth\AuthProviderEnum;
use Hyvor\Internal\Auth\Providers\Fake\FakeProvider;
use Hyvor\Internal\Auth\Providers\Hyvor\HyvorProvider;

class CurrentProvider
{

    public static function get() : AuthProviderEnum
    {
        $provider = strval(config('internal.auth.provider'));
        return AuthProviderEnum::from($provider);
    }

    public static function getImplementation() : ProviderInterface
    {
        $provider = self::get();

        return match ($provider) {
            AuthProviderEnum::HYVOR => new HyvorProvider,
            AuthProviderEnum::FAKE => new FakeProvider
        };
    }

}