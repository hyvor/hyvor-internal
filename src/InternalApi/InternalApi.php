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

    public function __construct(public ComponentType $component) {}

    public static function fromConfig() : self
    {
        return new self(ComponentType::fromConfig());
    }

    /**
     * @param array<mixed> $data
     * @return array<mixed>
     */
    public function call(
        ComponentType $to,
        /**
         * This is the part after the `/api/internal/` in the URL
         * ex: set `delete-user` to call `/api/internal/delete-user`
         */
        string $endpoint,
        array $data = []
    ) : array
    {

        $endpoint = ltrim($endpoint, '/');
        $url = $this->component->getUrlOf($to) . '/api/internal/' . $endpoint;

        $json = json_encode($data);
        if ($json === false) {
            throw new \Exception('Failed to encode data to JSON');
        }

        $message = Crypt::encryptString($json);

        try {
            $response = Http::post($url, [
                'from' => $this->component->value,
                'to' => $to->value,
                'message' => $message,
            ]);
        } catch (ConnectionException $e) { // @phpstan-ignore-line
            throw new InternalApiCallFailedException(
                'Internal API call failed. Connection error: ' . $e->getMessage(),
            );
        }

        if (!$response->ok()) {
            throw new InternalApiCallFailedException(
                'Internal API call failed. Status code: ' .
                $response->status() . ' - ' .
                $response->body(),
            );
        }

        return (array) $response->json();

    }

}