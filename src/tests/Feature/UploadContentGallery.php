<?php
namespace WebAppId\Content\Tests;

use Illuminate\Http\UploadedFile;

class UploadFeatureTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testUploadGallery(){
        $file = array('photos' => UploadedFile::fake()->image('file.png', 600, 600), 'name'=>'file.png', 'content_id' => '1');
        //dd($file);
        $result = $this->withSession(['timezone' => 'Asia/Jakarta'])->post($this->prefix_route . '/content/gallery', $file);
        dd($result);
    }
}