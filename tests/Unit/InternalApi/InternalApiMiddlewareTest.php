<?php

namespace Hyvor\Internal\Tests\Unit\InternalApi;

use Hyvor\Internal\Http\Exceptions\HttpException;
use Hyvor\Internal\InternalApi\Middleware\InternalApiMiddleware;
use Illuminate\Support\Facades\Crypt;

it('decrypts message and sets request attributes', function() {

    $request = new \Illuminate\Http\Request();
    $request->replace([
        'message' => Crypt::encryptString(json_encode([
            'user_id' => 123,
            'ids' => [1, 2, 3],
        ]))
    ]);

    $middleware = new InternalApiMiddleware();
    $middleware->handle($request, function ($request) {
        expect($request->user_id)->toBe(123);
        expect($request->ids)->toBe([1, 2, 3]);
    });


});

describe('fail', function() {

    it('on missing message', function() {

        $request = new \Illuminate\Http\Request();
        $middleware = new InternalApiMiddleware();
        $middleware->handle($request, fn() => null);

    })->throws(HttpException::class, 'Invalid message');

    it('on invalid message', function() {

        $request = new \Illuminate\Http\Request();
        $request->replace([
            'message' => 'invalid'
        ]);

        $middleware = new InternalApiMiddleware();
        $middleware->handle($request, fn() => null);

    })->throws(HttpException::class, 'Failed to decrypt message');

    it('on invalid data', function() {

        $request = new \Illuminate\Http\Request();
        $request->replace([
            'message' => Crypt::encryptString('invalid')
        ]);

        $middleware = new InternalApiMiddleware();
        $middleware->handle($request, fn() => null);

    })->throws(HttpException::class, 'Invalid data');

});