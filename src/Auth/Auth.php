<?php

namespace Hyvor\Helper\Auth;

use Hyvor\Helper\Auth\Providers\CurrentProvider;
use Hyvor\Login\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class Auth
{

    public static function check() : false|AuthUser
    {
        return CurrentProvider::getImplementation()->check();
    }

    public static function login() : RedirectResponse|Redirector
    {
        return CurrentProvider::getImplementation()->login();
    }

    public static function signup() : RedirectResponse|Redirector
    {
        return CurrentProvider::getImplementation()->signup();
    }

    public static function logout() : RedirectResponse|Redirector
    {
        return CurrentProvider::getImplementation()->logout();
    }

}
