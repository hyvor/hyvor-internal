<?php

namespace Hyvor\Helper\Media;

use Illuminate\Support\ServiceProvider;

class MediaServiceProvider extends ServiceProvider
{

    public function boot() : void
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/media.php');
    }

}