<?php

namespace Hyvor\Helper\Tests;

use Hyvor\Helper\Auth\AuthServiceProvider;
class TestCase extends \Orchestra\Testbench\TestCase
{
    /*public function setUp(): void
    {
        parent::setUp();
    }*/
    protected function getPackageProviders($app)
    {
        return [
            AuthServiceProvider::class,
        ];
    }

}
