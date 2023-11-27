<?php declare(strict_types=1);

namespace Hyvor\Login;

use Illuminate\Support\ServiceProvider;

class LoginServiceProvider extends ServiceProvider
{
    public function boot()
    {
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'hyvor-helper');
    }
}
