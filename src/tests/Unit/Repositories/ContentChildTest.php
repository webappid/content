<?php

namespace WebAppId\Content\Tests\Unit\Repositories;

use WebAppId\Content\Models\Content;
use WebAppId\Content\Repositories\ContentChildRepository;
use WebAppId\Content\Services\Params\AddContentChildParam;
use WebAppId\Content\Tests\TestCase;

class ContentChildTest extends TestCase
{
    
    private $userId = '1';
    
    private $contentChildRepository;
    
    private $contentTest;
    
    private $resultContentChild;
    
    private $resultContentParent;
    
    
    public function createDummyContent(): ?Content
    {
        return $this->getContentTest()->createContent($this->getContentTest()->getDummy());
    }
    
    public function contentChildRepository(): ContentChildRepository
    {
        if ($this->contentChildRepository == null) {
            $this->contentChildRepository = $this->getContainer()->make(ContentChildRepository::class);
        }
        return $this->contentChildRepository;
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
    
    public function addContentChild(): AddContentChildParam
    {
        $this->resultContentParent = $this->createDummyContent();
        $this->resultContentChild = $this->createDummyContent();
        $objContentChild = new AddContentChildParam();
        $objContentChild->setContentParentId($this->resultContentParent->id);
        $objContentChild->setContentChildId($this->resultContentChild->id);
        $objContentChild->setUserId($this->userId);
        return $objContentChild;
    }
    
    public function testContentChild(): void
    {
        $dummyData = $this->addContentChild();
        $result = $this->getContainer()->call([$this->contentChildRepository(), 'addContentChild'], ['addContentChildParam' => $dummyData]);
        
        if ($result != false) {
            $this->assertTrue(true);
        } else {
            $this->assertTrue(false);
        }
    }
    
    public function testGetContentChild(): void
    {
        $dummyData = $this->addContentChild();
        $result = $this->getContainer()->call([$this->contentChildRepository(), 'addContentChild'], ['addContentChildParam' => $dummyData]);
        
        if ($result == false) {
            $this->assertTrue(false);
        } else {
            
            $child = $this->resultContentParent->childs;
            
            $this->assertEquals($child[0]->title, $this->resultContentChild->title);
            $this->assertEquals($child[0]->code, $this->resultContentChild->code);
            $this->assertEquals($child[0]->description, $this->resultContentChild->description);
            $this->assertEquals($child[0]->keyword, $this->resultContentChild->keyword);
            $this->assertEquals($child[0]->og_title, $this->resultContentChild->og_title);
            $this->assertEquals($child[0]->og_description, $this->resultContentChild->og_description);
            $this->assertEquals($child[0]->default_image, $this->resultContentChild->default_image);
            $this->assertEquals($child[0]->status_id, $this->resultContentChild->status_id);
            $this->assertEquals($child[0]->language_id, $this->resultContentChild->language_id);
            $this->assertEquals($child[0]->publish_date, $this->resultContentChild->publish_date);
            $this->assertEquals($child[0]->additional_info, $this->resultContentChild->additional_info);
            $this->assertEquals($child[0]->content, $this->resultContentChild->content);
            $this->assertEquals($child[0]->time_zone_id, $this->resultContentChild->time_zone_id);
            $this->assertEquals($child[0]->owner_id, $this->resultContentChild->owner_id);
            $this->assertEquals($child[0]->user_id, $this->resultContentChild->user_id);
        }
    }
    
    public function testGetContentParent(): void
    {
        $dummyData = $this->addContentChild();
    
        $result = $this->getContainer()->call([$this->contentChildRepository(), 'addContentChild'], ['addContentChildParam' => $dummyData]);
        if ($result == false) {
            $this->assertTrue(false);
        } else {
            $parent = $this->resultContentChild->parents;
            
            $this->assertEquals($parent[0]->title, $this->resultContentParent->title);
            $this->assertEquals($parent[0]->code, $this->resultContentParent->code);
            $this->assertEquals($parent[0]->description, $this->resultContentParent->description);
            $this->assertEquals($parent[0]->keyword, $this->resultContentParent->keyword);
            $this->assertEquals($parent[0]->og_title, $this->resultContentParent->og_title);
            $this->assertEquals($parent[0]->og_description, $this->resultContentParent->og_description);
            $this->assertEquals($parent[0]->default_image, $this->resultContentParent->default_image);
            $this->assertEquals($parent[0]->status_id, $this->resultContentParent->status_id);
            $this->assertEquals($parent[0]->language_id, $this->resultContentParent->language_id);
            $this->assertEquals($parent[0]->publish_date, $this->resultContentParent->publish_date);
            $this->assertEquals($parent[0]->additional_info, $this->resultContentParent->additional_info);
            $this->assertEquals($parent[0]->content, $this->resultContentParent->content);
            $this->assertEquals($parent[0]->time_zone_id, $this->resultContentParent->time_zone_id);
            $this->assertEquals($parent[0]->owner_id, $this->resultContentParent->owner_id);
            $this->assertEquals($parent[0]->user_id, $this->resultContentParent->user_id);
        }
    }
    
    public function testDeleteContentChildById(): void
    {
        $dummyData = $this->addContentChild();
    
        $result = $this->getContainer()->call([$this->contentChildRepository(), 'addContentChild'], ['addContentChildParam' => $dummyData]);
        if ($result == false) {
            $this->assertTrue(false);
        } else {
            
            $resultDelete = $this->getContainer()->call([$this->contentChildRepository(), 'deleteContentChild'], ['id' => $result->id]);
            if ($resultDelete) {
                $this->assertTrue(true);
            } else {
                $this->assertTrue(false);
            }
        }
    }
    
    public function testDeleteContentChildByParentId(): void
    {
        $dummyData = $this->addContentChild();
    
        $result = $this->getContainer()->call([$this->contentChildRepository(), 'addContentChild'], ['addContentChildParam' => $dummyData]);
        if ($result == false) {
            $this->assertTrue(false);
        } else {
            
            $resultDelete = $this->getContainer()->call([$this->contentChildRepository(), 'deleteContentChild'], ['id' => $this->resultContentParent->id]);
            
            if ($resultDelete) {
                $this->assertTrue(true);
            } else {
                $this->assertTrue(false);
            }
        }
    }
}
