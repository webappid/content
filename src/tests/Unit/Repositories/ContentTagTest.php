<?php

namespace WebAppId\Content\Tests\Unit\Repositories;

use WebAppId\Content\Repositories\ContentTagRepository;
use WebAppId\Content\Tests\TestCase;

class ContentTagTest extends TestCase
{
    private $contentTest;
    
    private $tagTest;
    
    private $contentTagRepository;
    
    private $resultTagTest;
    
    private $resultContentTest;
    
    public function setUp()
    {
        parent::setUp();
        $this->getContentTest()->setUp();
        $this->getTagTest()->setUp();
        $this->contentTagRepository = new ContentTagRepository;
    }
    
    private function getContentTest()
    {
        if ($this->contentTest == null) {
            $this->contentTest = new ContentTest;
        }
        
        return $this->contentTest;
    }
    
    private function getTagTest()
    {
        if ($this->tagTest == null) {
            $this->tagTest = new TagTest;
        }
        
        return $this->tagTest;
    }
    
    
    public function createDummyContent()
    {
        return $this->getContentTest()->createContent($this->getContentTest()->getDummy());
    }
    
    public function createDummyTag()
    {
        return $this->getTagTest()->createTag($this->getTagTest()->getDummy());
    }
    
    public function getDummy()
    {
        $this->resultContentTest = $this->createDummyContent();
        $this->resultTagTest = $this->createDummyTag();
        
        $dummy = new \StdClass;
        
        $dummy->content_id = $this->resultContentTest->id;
        $dummy->tag_id = $this->resultTagTest->id;
        $dummy->user_id = '1';
        return $dummy;
    }
    
    public function createContentTag()
    {
        $dummy = $this->getDummy();
        $resultContentTag = $this->getContainer()->call([$this->contentTagRepository, 'addContentTag'], ['response' => $dummy]);
        
        if (!$resultContentTag) {
            $this->assertTrue(false);
        } else {
            $this->assertEquals($dummy->content_id, $resultContentTag->content_id);
            $this->assertEquals($dummy->tag_id, $resultContentTag->tag_id);
            return $resultContentTag;
        }
    }
    
    public function testAddContentTag()
    {
        $resultContentTag = $this->createContentTag();
        if ($resultContentTag != false) {
            $this->assertTrue(true);
        }
    }
    
    public function testCheckContentTag()
    {
        $resultContentTag = $this->createContentTag();
        if ($resultContentTag != false) {
            $this->assertTrue(true);
            
            $this->assertEquals($this->resultContentTest->tag[0]->name, $this->resultTagTest->name);
        }
    }
}