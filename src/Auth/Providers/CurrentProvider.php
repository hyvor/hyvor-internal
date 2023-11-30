<?php declare(strict_types=1);

namespace Hyvor\Helper\Auth\Providers;

use Hyvor\Helper\Auth\AuthProviderEnum;
use Hyvor\Helper\Auth\Providers\Fake\FakeProvider;
use Hyvor\Helper\Auth\Providers\Hyvor\HyvorProvider;
use Hyvor\Helper\Auth\Providers\Oidc\OidcProvider;

class CurrentProvider
{

    public static function get() : AuthProviderEnum
    {
        $provider = strval(config('hyvor-helper.auth.provider'));
        return AuthProviderEnum::from($provider);
    }

    public static function getImplementation() : ProviderInterface
    {
        $provider = self::get();

        return match ($provider) {
            AuthProviderEnum::HYVOR => new HyvorProvider,
            AuthProviderEnum::FAKE => new FakeProvider,
            AuthProviderEnum::OIDC => new OidcProvider
        };
    }

}