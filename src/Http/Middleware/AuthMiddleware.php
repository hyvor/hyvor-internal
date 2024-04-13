<?php

namespace Hyvor\Internal\Http\Middleware;

use Closure;
use Hyvor\Internal\Auth\Auth;
use Hyvor\Internal\Http\Exceptions\HttpException;
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