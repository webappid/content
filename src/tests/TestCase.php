<?php

namespace WebAppId\Content\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;

use Faker\Factory as Faker;

abstract class TestCase extends BaseTestCase
{
    protected $faker;
    /**
     * Set up the test
     */
    public function setUp()
    {
        parent::setUp();
        $this->faker = Faker::create();

        $this->loadMigrationsFrom([
            '--realpath' => realpath(__DIR__.'/../src/migrations'),
        ]);
        $this->artisan('webappid:content:seed');

    }

    protected function getPackageProviders($app)
    {
        return [
            \WebAppId\Content\ServiceProvider::class
        ];
    }
    protected function getPackageAliases($app)
    {
        return [
            'Content' => \WebAppId\Content\Facade::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:'
        ]);
    }
}
