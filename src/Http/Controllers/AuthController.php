<?php

namespace Hyvor\Helper\Http\Controllers;

use Hyvor\Helper\Auth\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class AuthController
{

    public function login() : RedirectResponse|Redirector
    {
        return Auth::login();
    }

    public function signup() : RedirectResponse|Redirector
    {
        return Auth::signup();
    }

    public function logout() : RedirectResponse|Redirector
    {
        return Auth::logout();
    }

}