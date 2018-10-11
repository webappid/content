<?php

namespace WebAppId\Content\Tests;

use WebAppId\Content\Tests\Unit\Models\ContentTest;
use Illuminate\Support\Facades\Session;
use WebAppId\Content\Tests\TestCase;

class ContentFeatureTest extends TestCase
{

    private $contentDummy;

    public function setUp()
    {
        parent::setUp();
        $contentTest = new ContentTest();
        $contentTest->setup();
        $contentDummy = $contentTest->createDummy();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCategory()
    {
        $response = $this->withSession(['content_test' => 'true'])->get($this->prefix_route . '/category');
        dd($response);
        $this->assertEquals(200, $response->status());
    }
}
