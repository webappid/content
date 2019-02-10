<?php

namespace WebAppId\Content\Tests;

use Illuminate\Http\UploadedFile;

class UploadFeatureTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }
    
    public function testUploadFile(): void
    {
        $file = array('photos' => UploadedFile::fake()->image('file.png', 600, 600), 'name' => 'file.png');
        
        $response = $this->withSession(['timezone' => 'Asia/Jakarta'])->post($this->prefix_route . '/file/upload/tmp', $file);
        
        $this->assertEquals(200, $response->status());
    }
}