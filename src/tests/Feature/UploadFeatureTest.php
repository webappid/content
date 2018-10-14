<?php
namespace WebAppId\Content\Tests;

use Illuminate\Http\UploadedFile;

class UploadFeatureTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testUploadFile(){
        $file = array('photos' => UploadedFile::fake()->image('file.png', 600, 600), 'name'=>'file.png');
        //dd($file);
        $result = $this->withSession(['timezone' => 'Asia/Jakarta'])->post($this->prefix_route . '/file/upload/tmp', $file);
        dd($result);
    }
}