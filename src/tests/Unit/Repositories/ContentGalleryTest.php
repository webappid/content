<?php

namespace WebAppId\Content\Tests\Unit\Repositories;

use WebAppId\Content\Repositories\ContentGalleryRepository;
use WebAppId\Content\Tests\TestCase;

class ContentGalleryTest extends TestCase
{
    
    private $contentGalleryRepository;
    
    private $contentTest;
    
    private $fileTest;
    
    private function getFileTest()
    {
        if ($this->fileTest == null) {
            $this->fileTest = new FileTest;
        }
        
        return $this->fileTest;
    }
    
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
        $this->contentGalleryRepository = new ContentGalleryRepository;
        $this->getContentTest()->setUp();
        $this->getFileTest()->setUp();
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
    
    public function createContent()
    {
        $contentResult = $this->getContentTest()->createContent($this->getContentTest()->getDummy());
        if ($contentResult == false) {
            $this->assertTrue(false);
        } else {
            return $contentResult;
        }
    }
    
    public function createFile()
    {
        $fileResult = $this->getFileTest()->createFile();
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
    
        $resultContentGallery = $this->getContainer()->call([$this->contentGalleryRepository, 'addContentGallery'], ['request' => $objContentGallery]);
        if ($resultContentGallery == false) {
            $this->assertTrue(false);
        } else {
            $this->assertTrue(true);
        }
    
        $fileResult = $this->createFile();
        $objContentGallery = $this->getDummy($contentResult->id, $fileResult->id);
    
        $resultContentGallery = $this->getContainer()->call([$this->contentGalleryRepository, 'addContentGallery'], ['request' => $objContentGallery]);
        if ($resultContentGallery == false) {
            $this->assertTrue(false);
        } else {
            $this->assertTrue(true);
        }
    }
    
    public function testDeleteFilesByContentId()
    {
        $contentResult = $this->createContent();
        $fileResult = $this->createFile();
        $objContentGallery = $this->getDummy($contentResult->id, $fileResult->id);
        
        $resultContentGallery = $this->getContainer()->call([$this->contentGalleryRepository, 'addContentGallery'], ['request' => $objContentGallery]);
        if ($resultContentGallery == false) {
            $this->assertTrue(false);
        } else {
            $this->assertTrue(true);
        }
        
        $fileResult = $this->createFile();
        $objContentGallery = $this->getDummy($contentResult->id, $fileResult->id);
        
        $resultContentGallery = $this->getContainer()->call([$this->contentGalleryRepository, 'addContentGallery'], ['request' => $objContentGallery]);
        if ($resultContentGallery == false) {
            $this->assertTrue(false);
        } else {
            $this->assertTrue(true);
        }
        
        $result = $this->getContainer()->call([$this->contentGalleryRepository, 'deleteContentGalleryByContentId'], ['content_id' => $contentResult->id]);
        if ($result) {
            $this->assertTrue(true);
        } else {
            $this->assertTrue(false);
        }
    }
}
