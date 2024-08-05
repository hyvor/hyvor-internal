<?php

namespace Hyvor\Internal\InternalApi\Middleware;

use Closure;
use Hyvor\Internal\Http\Exceptions\HttpException;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class InternalApiMiddleware
{

    public function handle(Request $request, Closure $next) : mixed
    {

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

        $request->replace($data);

        return $next($request);
    }

}