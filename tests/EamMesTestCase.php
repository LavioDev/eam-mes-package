<?php

namespace Spatie\LaravelPackageTools\Tests;

use Spatie\LaravelPackageTools\EamMesPackageServiceProvider;

class EamMesTestCase extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            EamMesPackageServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
}
