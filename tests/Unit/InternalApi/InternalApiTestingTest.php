<?php

namespace Hyvor\Internal\Tests\Unit\InternalApi;

use Hyvor\Internal\InternalApi\Testing\InternalApiTesting;

it('calls self', function() {

    InternalApiTesting::call(
        'GET',
        '/internal-api-testing-test-route',
        [
            'test' => 'test'
        ]
    )
        ->assertOk()
        ->assertJsonPath('test', 'test');

});

it('calls post', function() {

    InternalApiTesting::call(
        'POST',
        '/internal-api-testing-test-route-post',
        [
            'test' => 'post'
        ]
    )
        ->assertOk()
        ->assertJsonPath('test', 'post');


});