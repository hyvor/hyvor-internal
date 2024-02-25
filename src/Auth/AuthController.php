<?php

namespace Hyvor\Helper\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Response;

class AuthController
{

    public function check() : JsonResponse
    {
        $user = Auth::check();

        return Response::json([
            'is_logged_in' => $user !== false,
            'user' => $user ? $user : null,
        ]);
    }

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