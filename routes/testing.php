<?php

use Hyvor\Internal\InternalApi\ComponentType;
use Hyvor\Internal\InternalApi\Middleware\InternalApiFromMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// used for InternalApiTesting testing
Route::get('/api/internal/internal-api-testing-test-route', fn(Request $request) => response()->json($request->all()));
Route::post('/api/internal/internal-api-testing-test-route-post', fn(Request $request) => response()->json($request->all()));

Route::middleware([InternalApiFromMiddleware::class . ':core'])
    ->get('/api/internal/internal-api-testing-test-route-from-middleware', fn() => 'ok');