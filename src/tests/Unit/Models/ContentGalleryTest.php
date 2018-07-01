<?php

namespace WebAppId\Content\Tests\Unit\Models;

use WebAppId\Content\Models\ContentGallery;
use WebAppId\Content\Tests\TestCase;
use WebAppId\Content\Tests\Unit\Models\ContentTest;

class ContentCategoryTest extends TestCase
{

    private $objContentGallery;

    private $contentGallery;
    private $contentTest;
    private $fileTest;

    public function start()
    {
        $this->contentGallery = new ContentGallery;
        $this->contentTest = new ContentTest;
        $this->contentTest->setUp();
        $this->fileTest = new FileTest;
        $this->fileTest->setUp();
        $this->objContentGallery = new \StdClass;
    }

    public function setUp()
    {
        parent::setUp();
        $this->start();
    }

    public function createDummy($content_id, $file_id)
    {
        $this->objContentGallery->content_id = $content_id;
        $this->objContentGallery->file_id = $file_id;
        $this->objContentGallery->user_id = '1';
        $this->objContentGallery->description = $this->faker->text($maxNbChars = 200);
        return $this->objContentGallery;
    }

    public function createContent(){
        $contentResult = $this->contentTest->createContent();
        if ($contentResult == false) {
            $this->assertTrue(false);
        }else{
            return $contentResult;
        }
    }

    public function createFile(){
        $fileResult = $this->fileTest->createFile();
        if ($fileResult == false) {
            $this->assertTrue(false);
        } else {
            return $fileResult;
        }
    }

    public function testCrateContentFile()
    {
         $contentResult = $this->createContent();
         $fileResult = $this->createFile();
         $objContentGallery = $this->createDummy($contentResult->id, $fileResult->id);
         
         $resultContentGallery = $this->contentGallery->addContentGallery($objContentGallery);
         if($resultContentGallery==false){
             $this->assertTrue(false);
         }else{
             $this->assertTrue(true);
         }

         $fileResult = $this->createFile();
         $objContentGallery = $this->createDummy($contentResult->id, $fileResult->id);
         
         $resultContentGallery = $this->contentGallery->addContentGallery($objContentGallery);
         if($resultContentGallery==false){
             $this->assertTrue(false);
         }else{
             $this->assertTrue(true);
         }
    }

    public function testDeleteFilesByContentId(){
        $contentResult = $this->createContent();
         $fileResult = $this->createFile();
         $objContentGallery = $this->createDummy($contentResult->id, $fileResult->id);
         
         $resultContentGallery = $this->contentGallery->addContentGallery($objContentGallery);
         if($resultContentGallery==false){
             $this->assertTrue(false);
         }else{
             $this->assertTrue(true);
         }

         $fileResult = $this->createFile();
         $objContentGallery = $this->createDummy($contentResult->id, $fileResult->id);
         
         $resultContentGallery = $this->contentGallery->addContentGallery($objContentGallery);
         if($resultContentGallery==false){
             $this->assertTrue(false);
         }else{
             $this->assertTrue(true);
         }

         $result = $this->contentGallery->deleteContentGalleryByContentId($contentResult->id);
         if($result){
             $this->assertTrue(true);
         }else{
             $this->assertTrue(false);
         }
    }
}
