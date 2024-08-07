<?php

namespace Hyvor\Internal\Tests\Unit\InternalApi;

use Hyvor\Internal\InternalApi\ComponentType;
use Hyvor\Internal\InternalApi\Exceptions\InternalApiCallFailedException;
use Hyvor\Internal\InternalApi\InternalApi;
use Hyvor\Internal\InternalApi\InternalApiMethod;
use Illuminate\Support\Facades\Http;

it('calls talk internal API', function() {

    $this->freezeTime();

    Http::fake([
        'talk.hyvor.com/api/internal/delete-user' => Http::response(['success' => true], 200)
    ]);

    InternalApi::call(
        ComponentType::TALK,
        'POST',
        'delete-user',
        ['user_id' => 123]
    );

    Http::assertSent(function ($request) {
        expect($request->url())->toBe('https://talk.hyvor.com/api/internal/delete-user');

        $message = $request['message'];
        $message = decrypt($message, false);
        $data = json_decode($message, true);

        expect($data['data']['user_id'])->toBe(123);
        expect($data['timestamp'])->toBe(now()->timestamp);

        $headers = $request->headers();
        expect($headers['X-Internal-Api-From'][0])->toBe('core');
        expect($headers['X-Internal-Api-To'][0])->toBe('talk');


        return true;
    });

});

it('calls with get', function() {

    Http::fake([
        'talk.hyvor.com/api/internal/sudo/users*' => Http::response(['success' => true], 200)
    ]);

    $response = InternalApi::call(
        ComponentType::TALK,
        InternalApiMethod::GET,
        '/sudo/users',
        ['user_id' => 123]
    );

    expect($response)->toBe(['success' => true]);

    Http::assertSent(function ($request) {
        expect($request->url())->toStartWith('https://talk.hyvor.com/api/internal/sudo/users');
        expect($request->method())->toBe('GET');

        $message = $request['message'];
        $message = decrypt($message, false);
        $data = json_decode($message, true);
        expect($data['data']['user_id'])->toBe(123);

        return true;
    });

});

it('throws an error if the response fails', function() {

    Http::fake([
        'talk.hyvor.com/api/internal/delete-user' => Http::response(['success' => false], 500)
    ]);

    try {
        InternalApi::call(
            ComponentType::TALK,
            'POST',
            'delete-user',
            ['user_id' => 123]
        );
    } catch (InternalApiCallFailedException $e) {
        expect($e->getMessage())->toBe('Internal API call to https://talk.hyvor.com/api/internal/delete-user failed. Status code: 500 - {"success":false}');
        return;
    }

    expect(true)->toBeFalse();

});

it('throws an error on connection exception', function() {

    Http::fake([
        'talk.hyvor.com/api/internal/delete-user' => function() {
            throw new \Illuminate\Http\Client\ConnectionException('Connection error');
        }
    ]);

    try {
        InternalApi::call(
            ComponentType::TALK,
            'POST',
            'delete-user',
            ['user_id' => 123]
        );
    } catch (InternalApiCallFailedException $e) {
        expect($e->getMessage())->toBe('Internal API call to https://talk.hyvor.com/api/internal/delete-user failed. Connection error: Connection error');
        return;
    }

    expect(true)->toBeFalse();

});