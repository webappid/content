<?php

namespace WebAppId\Content\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use WebAppId\Content\ServiceProvider;
use WebAppId\DDD\Traits\TestCaseTrait;

abstract class TestCase extends BaseTestCase
{
    use TestCaseTrait;

    /**
     * Set up the test
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom([
            '--realpath' => realpath(__DIR__ . '/src/migrations'),
        ]);

        $this->artisan('webappid:content:seed');
    }

    protected function getPackageProviders($app)
    {
        return [
            \WebAppId\User\ServiceProvider::class,
            ServiceProvider::class
        ];
    }

}
