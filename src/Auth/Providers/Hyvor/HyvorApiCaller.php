<?php

namespace Hyvor\Internal\Auth\Providers\Hyvor;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class HyvorApiCaller
{

    /**
     * @param string $endpoint
     * @param array<string, string> $data
     * @param array<string, string> $headers
     */
    public static function call(
        string $endpoint,
        array $data = [],
        array $headers = []
    ) : Response
    {

        $data['key'] = config('hyvor-internal.auth.hyvor.api_key');
        $endpoint = ltrim($endpoint, '/');

        return Http::withHeaders($headers)->post(
            url: config('hyvor-internal.auth.hyvor.private_url') . '/api/auth/' . $endpoint,
            data: $data
        );

    }

}