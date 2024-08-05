<?php

namespace Hyvor\Internal\Tests\Feature;

use Hyvor\Internal\InternalServiceProvider;
use Illuminate\Routing\RouteCollection;
use Illuminate\Support\Facades\Route;

it('does not add on other domains', function() {

    $this->app->register(InternalServiceProvider::class, true);

    config(['internal.domain' => 'hyvor.com']);
    Route::setRoutes(new RouteCollection());
    (new InternalServiceProvider($this->app))->boot();

    $this
        ->post('https://nothyvor.com/api/auth/check')
        ->assertStatus(404);
});

it('works with current domain', function() {

    $this->app->register(InternalServiceProvider::class, true);

    config(['internal.domain' => 'hyvor.com']);
    Route::setRoutes(new RouteCollection());
    (new InternalServiceProvider($this->app))->boot();

    $this
        ->post('https://hyvor.com/api/auth/check')
        ->assertOk();

});