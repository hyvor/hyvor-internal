<?php

use Illuminate\Support\Facades\Route;

Route::domain(config('hyvor-helper.domain'))->group(function() {
    include 'auth.php';
    include 'media.php';
});