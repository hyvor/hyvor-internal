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

        $json = json_encode($data);
        if ($json === false) {
            throw new \Exception('Failed to encode data to JSON');
        }

        $message = Crypt::encryptString($json);

        $headers = [
            'X-Internal-Api-From' => ComponentType::current()->value,
            'X-Internal-Api-To' => $to->value,
        ];

        try {
            $response = Http::$methodFunction($url, [
                'message' => $message,
            ], $headers);
        } catch (ConnectionException $e) { // @phpstan-ignore-line
            throw new InternalApiCallFailedException(
                'Internal API call failed. Connection error: ' . $e->getMessage(),
            );
        }

        if (!$response->ok()) {
            throw new InternalApiCallFailedException(
                'Internal API call failed. Status code: ' .
                $response->status() . ' - ' .
                substr($response->body(), 0, 250)
            );
        }

        return (array) $response->json();

    }

}