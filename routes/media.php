<?php

use Hyvor\Internal\Media\MediaController;
use Illuminate\Support\Facades\Route;

Route::get(config('internal.media.path') . '/{path}', [MediaController::class, 'serve'])
    ->where('path', '.*');