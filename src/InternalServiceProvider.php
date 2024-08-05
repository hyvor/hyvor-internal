<?php

namespace Hyvor\Internal;

use Hyvor\Internal\InternalApi\InternalApi;
use Illuminate\Support\ServiceProvider;

class InternalServiceProvider extends ServiceProvider
{

    public function boot() : void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'internal');
    }

}