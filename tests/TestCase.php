<?php

namespace Hyvor\Helper\Tests;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /*public function setUp(): void
    {
        parent::setUp();
    }*/
    protected function getPackageProviders($app)
    {
        $composer = json_decode(file_get_contents(__DIR__ . '/../composer.json'), true);
        $providers = $composer['extra']['laravel']['providers'] ?? [];
        return $providers;
    }

}
