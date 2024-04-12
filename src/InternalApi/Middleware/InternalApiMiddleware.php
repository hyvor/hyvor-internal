<?php

namespace Hyvor\Internal\InternalApi\Middleware;

use Closure;
use Illuminate\Routing\Controllers\Middleware;

class InternalApiMiddleware extends Middleware
{

    public function handle($request, Closure $next)
    {
        if ($request->header('X-Hyvor-Internal-Api-Key') !== config('internal-api.key')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }

}