<?php

namespace Hyvor\Internal;

use Hyvor\Internal\InternalApi\InternalApi;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class InternalServiceProvider extends ServiceProvider
{

    public function boot() : void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        if (App::environment('testing')) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/testing.php');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'internal');
    }

}