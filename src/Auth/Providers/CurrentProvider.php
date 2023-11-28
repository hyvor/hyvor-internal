<?php declare(strict_types=1);

namespace Hyvor\Helper\Auth\Providers;

use Hyvor\Helper\Auth\Providers\Fake\FakeProvider;

class CurrentProvider
{

    public static function get() : ProviderInterface
    {
        return new FakeProvider;
    }

}