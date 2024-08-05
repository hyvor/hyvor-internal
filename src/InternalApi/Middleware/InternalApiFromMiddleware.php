<?php

namespace Hyvor\Internal\InternalApi\Middleware;

use Hyvor\Internal\InternalApi\ComponentType;
use Illuminate\Http\Request;

class InternalApiFromMiddleware
{


    public function handle(Request $request, \Closure $next, string $input) : mixed
    {

        $from = ComponentType::from($input);

        $fromHeader = $request->header('X-Internal-Api-From');

        if (!$fromHeader) {
            abort(403, 'Missing from component');
        }

        if ($fromHeader !== $from->value) {
            abort(403, 'Invalid from component');
        }

        return $next($request);
    }

}