<?php

namespace WebAppId\Content\Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Faker\Factory as Faker;
use Test\CreateApplication;

abstract class TestCase extends BaseTestCase
{
    use DatabaseMigrations, DatabaseTransactions;


    protected $faker;
    /**
     * Set up the test
     */
    public function setUp()
    {
        parent::setUp();
        $this->faker = Faker::create();
        $this->artisan('migrate');
        $this->artisan('db:seed');
    }

    /**
     * Create Aplication set for fake connection
     *
     * @return void
     */
    public function createApplication(){
        putenv('DB_CONNECTION=sqlite');
        putenv('DB_DATABASE=:memory:');
        $app = require __DIR__ . '/../../../../../bootstrap/app.php';
        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
        return $app;
    }

    /**
     * Reset the migrations
     */
    public function tearDown()
    {
        $this->artisan('migrate:reset');
        parent::tearDown();
    }
}
