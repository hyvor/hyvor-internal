<?php

namespace Hyvor\Internal\Internationalization;

use Illuminate\Support\ServiceProvider;

class InternationalizationServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton(I18n::class, function($app) {
            return new I18n();
        });
    }

}