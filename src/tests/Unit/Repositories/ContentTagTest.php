<?php

namespace WebAppId\Content\Tests\Unit\Repositories;

use WebAppId\Content\Models\Content;
use WebAppId\Content\Models\ContentTag;
use WebAppId\Content\Models\Tag;
use WebAppId\Content\Repositories\ContentTagRepository;
use WebAppId\Content\Tests\TestCase;

class ContentTagTest extends TestCase
{
    private $contentTest;
    
    private $tagTest;
    
    private $contentTagRepository;
    
    private $resultTagTest;
    
    private $resultContentTest;
    
    private function contentTagRepository(): ContentTagRepository
    {
        if ($this->contentTagRepository == null) {
            $this->contentTagRepository = new ContentTagRepository();
        }
        return $this->contentTagRepository;
    }
    
    public function setUp(): void
    {
        parent::setUp();
    }
    
    private function getContentTest(): ?ContentTest
    {
        if ($this->contentTest == null) {
            $this->contentTest = new ContentTest;
        }
        
        return $this->contentTest;
    }
    
    private function getTagTest(): ?TagTest
    {
        if ($this->tagTest == null) {
            $this->tagTest = new TagTest;
        }
        
        return $this->tagTest;
    }
    
    
    public function createDummyContent(): ?Content
    {
        return $this->getContentTest()->createContent($this->getContentTest()->getDummy());
    }
    
    public function createDummyTag(): ?Tag
    {
        return $this->getTagTest()->createTag($this->getTagTest()->getDummy());
    }
    
    public function getDummy(): object
    {
        $this->resultContentTest = $this->createDummyContent();
        $this->resultTagTest = $this->createDummyTag();
        
        $dummy = new \StdClass;
        
        $dummy->content_id = $this->resultContentTest->id;
        $dummy->tag_id = $this->resultTagTest->id;
        $dummy->user_id = '1';
        return $dummy;
    }
    
    public function createContentTag(): ?ContentTag
    {
        $dummy = $this->getDummy();
        $resultContentTag = $this->getContainer()->call([$this->contentTagRepository(), 'addContentTag'], ['response' => $dummy]);
        
        if (!$resultContentTag) {
            $this->assertTrue(false);
        } else {
            $this->assertEquals($dummy->content_id, $resultContentTag->content_id);
            $this->assertEquals($dummy->tag_id, $resultContentTag->tag_id);
            return $resultContentTag;
        }
    }
    
    public function testAddContentTag(): void
    {
        $resultContentTag = $this->createContentTag();
        if ($resultContentTag != false) {
            $this->assertTrue(true);
        }
    }
    
    public function testCheckContentTag(): void
    {
        $resultContentTag = $this->createContentTag();
        if ($resultContentTag != false) {
            $this->assertTrue(true);
            
            $this->assertEquals($this->resultContentTest->tags[0]->name, $this->resultTagTest->name);
        }
    }
}