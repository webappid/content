<?php

namespace WebAppId\Content\Tests\Unit\Repositories;

use WebAppId\Content\Models\Content;
use WebAppId\Content\Models\File;
use WebAppId\Content\Repositories\ContentGalleryRepository;
use WebAppId\Content\Tests\TestCase;

class ContentGalleryTest extends TestCase
{
    
    private $contentGalleryRepository;
    
    private $contentTest;
    
    private $fileTest;
    
    private function getFileTest(): FileTest
    {
        if ($this->fileTest == null) {
            $this->fileTest = new FileTest;
        }
        
        return $this->fileTest;
    }
    
    private function contentGalleryRepository(): ContentGalleryRepository
    {
        if ($this->contentGalleryRepository == null) {
            $this->contentGalleryRepository = $this->contentGalleryRepository = new ContentGalleryRepository;
        }
        return $this->contentGalleryRepository;
    }
    
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
    
    public function getDummy(int $content_id, int $file_id): object
    {
        $objContentGallery = new \StdClass;
        $objContentGallery->content_id = $content_id;
        $objContentGallery->file_id = $file_id;
        $objContentGallery->user_id = '1';
        $objContentGallery->description = $this->getFaker()->text($maxNbChars = 200);
        return $objContentGallery;
    }
    
    public function createContent(): ?Content
    {
        $contentResult = $this->getContentTest()->createContent($this->getContentTest()->getDummy());
        if ($contentResult == false) {
            $this->assertTrue(false);
        } else {
            return $contentResult;
        }
    }
    
    public function createFile(): ?File
    {
        $dummyFile = $this->getFileTest()->createDummy();
        $fileResult = $this->getFileTest()->createFile($dummyFile);
        if ($fileResult == false) {
            $this->assertTrue(false);
        } else {
            return $fileResult;
        }
    }
    
    public function testCrateContentFile(): void
    {
        $contentResult = $this->createContent();
        $dummyFile = $this->getFileTest()->createDummy();
        $fileResult = $this->getFileTest()->createFile($dummyFile);
        $objContentGallery = $this->getDummy($contentResult->id, $fileResult->id);
        
        $resultContentGallery = $this->getContainer()->call([$this->contentGalleryRepository(), 'addContentGallery'], ['request' => $objContentGallery]);
        if ($resultContentGallery == false) {
            $this->assertTrue(false);
        } else {
            $this->assertTrue(true);
        }
        
        $fileResult = $this->createFile();
        $objContentGallery = $this->getDummy($contentResult->id, $fileResult->id);
        
        $resultContentGallery = $this->getContainer()->call([$this->contentGalleryRepository(), 'addContentGallery'], ['request' => $objContentGallery]);
        if ($resultContentGallery == false) {
            $this->assertTrue(false);
        } else {
            $this->assertTrue(true);
        }
    }
    
    public function testDeleteFilesByContentId(): void
    {
        $contentResult = $this->createContent();
        $dummyFile = $this->getFileTest()->createDummy();
        $fileResult = $this->getFileTest()->createFile($dummyFile);
        $objContentGallery = $this->getDummy($contentResult->id, $fileResult->id);
        
        $resultContentGallery = $this->getContainer()->call([$this->contentGalleryRepository(), 'addContentGallery'], ['request' => $objContentGallery]);
        if ($resultContentGallery == false) {
            $this->assertTrue(false);
        } else {
            $this->assertTrue(true);
        }
        
        $fileResult = $this->createFile();
        $objContentGallery = $this->getDummy($contentResult->id, $fileResult->id);
        
        $resultContentGallery = $this->getContainer()->call([$this->contentGalleryRepository(), 'addContentGallery'], ['request' => $objContentGallery]);
        if ($resultContentGallery == false) {
            $this->assertTrue(false);
        } else {
            $this->assertTrue(true);
        }
        
        $result = $this->getContainer()->call([$this->contentGalleryRepository(), 'deleteContentGalleryByContentId'], ['content_id' => $contentResult->id]);
        if ($result) {
            $this->assertTrue(true);
        } else {
            $this->assertTrue(false);
        }
    }
}
