<?php

namespace Hyvor\Internal\InternalApi\Testing;

use Hyvor\Internal\InternalApi\ComponentType;
use Hyvor\Internal\InternalApi\InternalApiMethod;
use Illuminate\Support\Facades\App;
use Illuminate\Testing\TestResponse;

/**
 * Use this class to test the internal API calls of a component
 */
class InternalApiTesting
{

    /**
     * @param array<mixed> $data
     * @param InternalApiMethod|'GET'|'POST' $method
     * @return array<mixed>
     */
    public static function call(
        InternalApiMethod|string $method,
        string $endpoint,
        array $data = [],
        ComponentType $from = null,
    ) : TestResponse
    {

        if (!App::environment('testing')) {
            throw new \Exception('This method can only be called in the testing environment');
        }

        if (is_string($method)) {
            $method = InternalApiMethod::from($method);
        }

        if (!function_exists('test')) {
            throw new \Exception('test() function of PestPHP not found');
        }

        $test = test();
        $endpoint = ltrim($endpoint, '/');

        return $test->call(
            $method->value,
            '/api/internal/' . $endpoint,
            $data,
            [],
            [],
            [
                'HTTP_X-Internal-Api-From' => ($from ?? ComponentType::CORE)->value,
                'HTTP_X-Internal-Api-To' => ComponentType::current()->value,
            ]
        );
        
    }

}