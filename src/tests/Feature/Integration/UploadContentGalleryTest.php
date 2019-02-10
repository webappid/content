<?php

namespace WebAppId\Content\Tests;

use Illuminate\Http\UploadedFile;
use WebAppId\Content\Models\Content;
use WebAppId\Content\Tests\Unit\Repositories\ContentTest;

class UploadContentGalleryTest extends TestCase
{
    private $contentTest;
    
    private function getContentTest(): ContentTest
    {
        if ($this->contentTest == null) {
            $this->contentTest = new ContentTest;
        }
        
        return $this->contentTest;
    }
    
    public function setUp(): void
    {
        parent::setUp();
    }
    
    private function getDummy(): ?Content
    {
        return $this->getContentTest()->createContent($this->getContentTest()->getDummy());
    }
    
    public function testUploadGallery(): void
    {
        $content = $this->getDummy();
        
        $file = array('photos' => UploadedFile::fake()->image('file.png', 600, 600), 'name' => 'file.png', 'content_id' => $content->id);
        $response = $this->withSession(['timezone' => 'Asia/Jakarta'])->post($this->prefix_route . '/content/gallery/tmp', $file);
        $this->assertEquals(201, $response->status());
    }
}