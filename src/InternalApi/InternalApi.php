<?php

namespace Hyvor\Internal\InternalApi;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

/**
 * Call the internal API between components
 */
class InternalApi
{

    public function __construct(public ComponentType $type) {}

    public function call() : Response
    {

        return Http::send('POST', config('hyvor-helper.internal_api_url'), [
            'type' => $this->type->value,
        ]);

    }

}