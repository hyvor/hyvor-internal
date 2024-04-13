<?php

namespace Hyvor\Internal\InternalApi\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;

class InternalApiMiddleware extends Middleware
{

    public function handle(Request $request, Closure $next) : mixed
    {
        return $next($request);
    }

}