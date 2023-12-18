<?php

namespace Hyvor\Helper\Auth;

use Hyvor\Helper\Auth\Providers\CurrentProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class Auth
{

    public static function check() : false|AuthUser
    {
        return CurrentProvider::getImplementation()->check();
    }

    public static function login(?string $redirect = null) : RedirectResponse|Redirector
    {
        return CurrentProvider::getImplementation()->login($redirect);
    }

    public static function signup(?string $redirect = null) : RedirectResponse|Redirector
    {
        return CurrentProvider::getImplementation()->signup($redirect);
    }

    public static function logout(?string $redirect = null) : RedirectResponse|Redirector
    {
        return CurrentProvider::getImplementation()->logout($redirect);
    }

}
