<?php

namespace WebAppId\Content\Tests\Unit\Repositories;

use WebAppId\Content\Repositories\ContentGalleryRepository;
use WebAppId\Content\Tests\TestCase;

use Illuminate\Container\Container;

class ContentGalleryTest extends TestCase
{
    
    private $contentGallery;
    private $contentTest;
    private $fileTest;

    private $container;

    public function start()
    {
        $this->contentGallery = new ContentGalleryRepository;
        $this->contentTest = new ContentTest;
        $this->contentTest->setUp();
        $this->fileTest = new FileTest;
        $this->fileTest->setUp();
        $this->container = new Container;
    }

    public function setUp()
    {
        parent::setUp();
        $this->start();
    }

    public function getDummy($content_id, $file_id)
    {
        $objContentGallery = new \StdClass;
        $objContentGallery->content_id = $content_id;
        $objContentGallery->file_id = $file_id;
        $objContentGallery->user_id = '1';
        $objContentGallery->description = $this->getFaker()->text($maxNbChars = 200);
        return $objContentGallery;
    }

    public function createContent(){
        $contentResult = $this->contentTest->createContent($this->contentTest->getDummy());
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
         $objContentGallery = $this->getDummy($contentResult->id, $fileResult->id);
         
         $resultContentGallery = $this->container->call([$this->contentGallery,'addContentGallery'],['request'=>$objContentGallery]);
         if($resultContentGallery==false){
             $this->assertTrue(false);
         }else{
             $this->assertTrue(true);
         }

         $fileResult = $this->createFile();
         $objContentGallery = $this->getDummy($contentResult->id, $fileResult->id);
         
         $resultContentGallery = $this->container->call([$this->contentGallery,'addContentGallery'],['request'=>$objContentGallery]);
         if($resultContentGallery==false){
             $this->assertTrue(false);
         }else{
             $this->assertTrue(true);
         }
    }

    public function testDeleteFilesByContentId(){
        $contentResult = $this->createContent();
         $fileResult = $this->createFile();
         $objContentGallery = $this->getDummy($contentResult->id, $fileResult->id);
         
         $resultContentGallery = $this->container->call([$this->contentGallery,'addContentGallery'],['request'=>$objContentGallery]);
         if($resultContentGallery==false){
             $this->assertTrue(false);
         }else{
             $this->assertTrue(true);
         }

         $fileResult = $this->createFile();
         $objContentGallery = $this->getDummy($contentResult->id, $fileResult->id);
         
         $resultContentGallery = $this->container->call([$this->contentGallery,'addContentGallery'],['request'=>$objContentGallery]);
         if($resultContentGallery==false){
             $this->assertTrue(false);
         }else{
             $this->assertTrue(true);
         }

         $result = $this->container->call([$this->contentGallery,'deleteContentGalleryByContentId'],['content_id'=>$contentResult->id]);
         if($result){
             $this->assertTrue(true);
         }else{
             $this->assertTrue(false);
         }
    }
}
