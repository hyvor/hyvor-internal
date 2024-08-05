<?php

use Illuminate\Support\Facades\Route;

Route::domain(config('internal.domain'))->group(function() {
    include 'auth.php';
    include 'media.php';
});