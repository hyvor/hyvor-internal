<?php

namespace Hyvor\Internal\InternalApi\Middleware;

use Closure;
use Hyvor\Internal\Http\Exceptions\HttpException;
use Hyvor\Internal\InternalApi\ComponentType;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class InternalApiMiddleware
{

    public function handle(Request $request, Closure $next) : mixed
    {

        $toHeader = (string) $request->header('X-Internal-Api-To');

        if ($toHeader !== ComponentType::current()->value) {
            throw new HttpException('Invalid to component', 403);
        }

        $message = $request->input('message');

        if (!is_string($message)) {
            throw new HttpException('Invalid message');
        }

        try {
            $data = Crypt::decryptString($message);
        } catch (DecryptException $exception) {
            throw new HttpException('Failed to decrypt message');
        }

        $data = json_decode($data, true);

        if (!is_array($data)) {
            throw new HttpException('Invalid data');
        }

        $timestamp = $data['timestamp'] ?? null;

        if (!is_int($timestamp)) {
            throw new HttpException('Invalid timestamp');
        }

        $diff = time() - $timestamp;

        if ($diff > 60) {
            throw new HttpException('Expired message');
        }

        $requestData = $data['data'] ?? [];

        if (!is_array($requestData)) {
            throw new HttpException('Invalid data');
        }

        $request->replace($requestData);

        return $next($request);
    }

}