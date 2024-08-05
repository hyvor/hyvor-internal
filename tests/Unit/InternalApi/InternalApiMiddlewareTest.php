<?php

namespace Hyvor\Internal\Tests\Unit\InternalApi;

use Hyvor\Internal\Http\Exceptions\HttpException;
use Hyvor\Internal\InternalApi\Middleware\InternalApiMiddleware;
use Illuminate\Support\Facades\Crypt;

it('decrypts message and sets request attributes', function() {

    $request = new \Illuminate\Http\Request();
    $request->replace([
        'message' => Crypt::encryptString(json_encode([
            'data' => [
                'user_id' => 123,
                'ids' => [1, 2, 3],
            ],
            'timestamp' => time()
        ]))
    ]);
    $request->headers->set('X-Internal-Api-To', 'core');

    $middleware = new InternalApiMiddleware();
    $middleware->handle($request, function ($request) {
        expect($request->user_id)->toBe(123);
        expect($request->ids)->toBe([1, 2, 3]);
    });


});

describe('fail', function() {

    it('rejects invalid to component', function() {

        $request = new \Illuminate\Http\Request();
        $request->headers->set('X-Internal-Api-To', 'talk');

        $middleware = new InternalApiMiddleware();
        $middleware->handle($request, fn() => null);

    })->throws(HttpException::class, 'Invalid to component', 403);

    it('on missing message', function() {

        $request = new \Illuminate\Http\Request();
        $request->headers->set('X-Internal-Api-To', 'core');

        $middleware = new InternalApiMiddleware();
        $middleware->handle($request, fn() => null);

    })->throws(HttpException::class, 'Invalid message');

    it('on missing timestamp', function() {

        $request = new \Illuminate\Http\Request();
        $request->replace([
            'message' => Crypt::encryptString(json_encode([
                'data' => [
                    'user_id' => 123,
                    'ids' => [1, 2, 3],
                ]
            ]))
        ]);
        $request->headers->set('X-Internal-Api-To', 'core');

        $middleware = new InternalApiMiddleware();
        $middleware->handle($request, fn() => null);

    })->throws(HttpException::class, 'Invalid timestamp');

    it('on expired message', function() {

        $request = new \Illuminate\Http\Request();
        $request->replace([
            'message' => Crypt::encryptString(json_encode([
                'data' => [
                    'user_id' => 123,
                    'ids' => [1, 2, 3],
                ],
                'timestamp' => time() - 65
            ]))
        ]);
        $request->headers->set('X-Internal-Api-To', 'core');

        $middleware = new InternalApiMiddleware();
        $middleware->handle($request, fn() => null);

    })->throws(HttpException::class, 'Expired message');

    it('on invalid message', function() {

        $request = new \Illuminate\Http\Request();
        $request->replace([
            'message' => 'invalid'
        ]);
        $request->headers->set('X-Internal-Api-To', 'core');

        $middleware = new InternalApiMiddleware();
        $middleware->handle($request, fn() => null);

    })->throws(HttpException::class, 'Failed to decrypt message');


    it('on invalid data', function() {

        $request = new \Illuminate\Http\Request();
        $request->replace([
            'message' => Crypt::encryptString('invalid')
        ]);
        $request->headers->set('X-Internal-Api-To', 'core');

        $middleware = new InternalApiMiddleware();
        $middleware->handle($request, fn() => null);

    })->throws(HttpException::class, 'Invalid data');

});