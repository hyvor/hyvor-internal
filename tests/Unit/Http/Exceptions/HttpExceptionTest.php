<?php

namespace Hyvor\Internal\Tests\Unit\Http\Exceptions;

use Hyvor\Internal\Http\Exceptions\HttpException;

it('creates with data', function() {
    $exception = new HttpException('message', 1001, ['key' => 'value']);
    expect($exception->getMessage())->toBe('message');
    expect($exception->getCode())->toBe(1001);
    expect($exception->data)->toBe(['key' => 'value']);
});