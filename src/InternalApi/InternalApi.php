<?php

namespace Hyvor\Internal\InternalApi;

use Hyvor\Internal\InternalApi\Exceptions\InternalApiCallFailedException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

/**
 * Call the internal API between components
 */
class InternalApi
{

    /**
     * @param array<mixed> $data
     * @param InternalApiMethod|'GET'|'POST' $method
     * @return array<mixed>
     */
    public static function call(
        ComponentType $to,
        InternalApiMethod|string $method,
        /**
         * This is the part after the `/api/internal/` in the URL
         * ex: set `/delete-user` to call `/api/internal/delete-user`
         */
        string $endpoint,
        array $data = []
    ) : array
    {

        if (is_string($method)) {
            $method = InternalApiMethod::from($method);
        }
        $methodFunction = strtolower($method->value);

        $endpoint = ltrim($endpoint, '/');
        $url = ComponentType::getUrlOf($to) . '/api/internal/' . $endpoint;

        $json = json_encode([
            'data' => $data,
            'timestamp' => time(),
        ]);
        if ($json === false) {
            throw new \Exception('Failed to encode data to JSON');
        }

        $message = Crypt::encryptString($json);

        $headers = [
            'X-Internal-Api-From' => ComponentType::current()->value,
            'X-Internal-Api-To' => $to->value,
        ];

        try {
            $response = Http::
                withHeaders($headers)
                ->$methodFunction($url, [
                    'message' => $message,
                ]);
        } catch (ConnectionException $e) {
            throw new InternalApiCallFailedException(
                'Internal API call to ' . $url . ' failed. Connection error: ' . $e->getMessage(),
            );
        }

        if (!$response->ok()) {
            throw new InternalApiCallFailedException(
                'Internal API call to ' . $url . ' failed. Status code: ' .
                $response->status() . ' - ' .
                substr($response->body(), 0, 250)
            );
        }

        return (array) $response->json();

    }

    public static function messageFromData(array $data) : string
    {

        $json = json_encode([
            'data' => $data,
            'timestamp' => time(),
        ]);
        if ($json === false) {
            throw new \Exception('Failed to encode data to JSON');
        }

        return Crypt::encryptString($json);

    }

}