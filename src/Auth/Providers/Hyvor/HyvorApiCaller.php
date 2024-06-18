<?php

namespace Hyvor\Internal\Auth\Providers\Hyvor;

use Hyvor\Internal\InternalApi\ComponentType;
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

        $endpoint = ltrim($endpoint, '/');
        $headers['X-Signature'] = encrypt(json_encode($data));

        $hyvorApiUrl = config('hyvor-internal.auth.hyvor.private_url') ?? ComponentType::fromConfig()->getCoreUrl();

        return Http::withHeaders($headers)
            ->asJson()
            ->post(
                url: $hyvorApiUrl . '/api/auth/' . $endpoint,
                data: $data
            );

    }

}