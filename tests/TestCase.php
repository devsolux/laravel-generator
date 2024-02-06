<?php

namespace Tests;

use DevSolux\Generator\GeneratorServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            GeneratorServiceProvider::class,
        ];
    }
}
