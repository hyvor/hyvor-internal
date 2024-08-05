<?php

namespace Hyvor\Internal\InternalApi\Middleware;

use Hyvor\Internal\InternalApi\ComponentType;
use Illuminate\Http\Request;

class InternalApiFromMiddleware
{


    public function handle(Request $request, \Closure $next, string $input)
    {

        $from = ComponentType::from($input);

        $fromHeader = (string) $request->header('X-Internal-Api-From');

        if ($fromHeader !== $from->value) {
            abort(403, 'Invalid from component');
        }

        return $next($request);
    }

}