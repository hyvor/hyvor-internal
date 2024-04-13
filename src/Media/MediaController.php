<?php

namespace Hyvor\Internal\Media;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\UnableToCheckExistence;
use League\Flysystem\UnableToRetrieveMetadata;

class MediaController
{

    public function serve(Request $request) : mixed
    {

        /** @var string $path */
        $path = $request->route('path');
        $missing = response(null, 404);

        if (!$path) {
            return $missing;
        }

        try {
            if (!Storage::exists($path)) {
                return $missing;
            }
        } catch (UnableToCheckExistence) {
            return $missing;
        }

        try {
            return Storage::response($path)->setCache([
                'etag' => true,
                'public' => true,
                'max_age' => 31536000,
            ]);
        } catch (UnableToRetrieveMetadata $e) {
            return $missing;
        }

    }

}