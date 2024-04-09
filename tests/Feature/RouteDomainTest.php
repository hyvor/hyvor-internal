<?php

namespace Hyvor\Internal\Tests\Feature;

use Hyvor\Internal\HelperServiceProvider;
use Illuminate\Routing\RouteCollection;
use Illuminate\Support\Facades\Route;

it('does not add on other domains', function() {

    $this->app->register(HelperServiceProvider::class, true);

    config(['hyvor-helper.domain' => 'hyvor.com']);
    Route::setRoutes(new RouteCollection());
    (new HelperServiceProvider($this->app))->boot();

    $this
        ->post('https://nothyvor.com/api/auth/check')
        ->assertStatus(404);
});

it('works with current domain', function() {

    $this->app->register(HelperServiceProvider::class, true);

    config(['hyvor-helper.domain' => 'hyvor.com']);
    Route::setRoutes(new RouteCollection());
    (new HelperServiceProvider($this->app))->boot();

    $this
        ->post('https://hyvor.com/api/auth/check')
        ->assertOk();

});