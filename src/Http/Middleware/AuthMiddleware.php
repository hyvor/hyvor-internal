<?php

namespace Hyvor\Helper\Http\Middleware;

use Closure;
use Hyvor\Helper\Auth\Auth;
use Hyvor\Helper\Http\Exceptions\HttpException;
use Illuminate\Http\Request;

class AuthMiddleware
{

    public function handle(Request $request, Closure $next) : mixed
    {
        $user = Auth::check();

        if (!$user) {
            throw new HttpException('Unauthorized', 401);
        }

        // @phpstan-ignore-next-line
        $accessUser = AccessAuthUser::fromArray(get_object_vars($user));
        app()->instance(AccessAuthUser::class, $accessUser);

        return $next($request);
    }

}