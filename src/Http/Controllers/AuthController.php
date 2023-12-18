<?php

namespace Hyvor\Helper\Http\Controllers;

use Hyvor\Helper\Auth\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class AuthController
{

    public function login(Request $request) : RedirectResponse|Redirector
    {
        return Auth::login($this->getRedirect($request));
    }

    public function signup(Request $request) : RedirectResponse|Redirector
    {
        return Auth::signup($this->getRedirect($request));
    }

    public function logout(Request $request) : RedirectResponse|Redirector
    {
        return Auth::logout($this->getRedirect($request));
    }

    private function getRedirect(Request $request) : ?string
    {
        return $request->get('redirect') ?? null;
    }

}