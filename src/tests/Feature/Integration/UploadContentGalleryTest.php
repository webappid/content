<?php
namespace WebAppId\Content\Tests;

use Illuminate\Http\UploadedFile;

use WebAppId\Content\Tests\Unit\Repositories\ContentTest;

class UploadContentGalleryTest extends TestCase
{
    private $contentTest;
    
    public function setUp()
    {
        parent::setUp();
        $this->start();
    }

    public function start(){
        $this->contentTest = new ContentTest;
        $this->contentTest->setUp();
    }

    private function getDummy()
    {
        return $this->contentTest->createContent($this->contentTest->getDummy());
    }

    public function testUploadGallery(){
        $content = $this->getDummy();
       
        $file = array('photos' => UploadedFile::fake()->image('file.png', 600, 600), 'name'=>'file.png', 'content_id' => $content->id);
        $response = $this->withSession(['timezone' => 'Asia/Jakarta'])->post($this->prefix_route . '/content/gallery/tmp', $file);
        $this->assertEquals(201, $response->status());
    }
}