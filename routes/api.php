<?php

use Illuminate\Support\Facades\Route;

Route::domain(config('hyvor-internal.domain'))->group(function() {
    include 'auth.php';
    include 'media.php';
});