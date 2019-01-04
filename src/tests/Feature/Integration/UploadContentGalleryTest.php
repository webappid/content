<?php

namespace WebAppId\Content\Tests;

use Illuminate\Http\UploadedFile;
use WebAppId\Content\Tests\Unit\Repositories\ContentTest;

class UploadContentGalleryTest extends TestCase
{
    private $contentTest;
    
    private function getContentTest()
    {
        if ($this->contentTest == null) {
            $this->contentTest = new ContentTest;
        }
        
        return $this->contentTest;
    }
    
    public function setUp()
    {
        parent::setUp();
        $this->getContentTest()->setUp();
    }
    
    private function getDummy()
    {
        return $this->getContentTest()->createContent($this->getContentTest()->getDummy());
    }
    
    public function testUploadGallery()
    {
        $content = $this->getDummy();
        
        $file = array('photos' => UploadedFile::fake()->image('file.png', 600, 600), 'name' => 'file.png', 'content_id' => $content->id);
        $response = $this->withSession(['timezone' => 'Asia/Jakarta'])->post($this->prefix_route . '/content/gallery/tmp', $file);
        $this->assertEquals(201, $response->status());
    }
}