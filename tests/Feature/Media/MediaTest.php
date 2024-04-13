<?php

namespace Hyvor\Internal\Tests\Feature\Media;

use Illuminate\Support\Facades\Storage;

it('serves media', function() {

    Storage::put(
        'files/test.txt',
        'test-contents'
    );

    $response = $this->get('/api/media/files/test.txt')
        ->assertOk()
        ->assertHeader('Cache-Control', 'max-age=31536000, public')
        ->assertHeader('ETag');

    expect($response->streamedContent())->toBe('test-contents');

});