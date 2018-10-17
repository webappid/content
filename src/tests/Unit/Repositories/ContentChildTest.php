<?php

namespace WebAppId\Content\Tests\Unit\Repositories;

use WebAppId\Content\Repositories\ContentChildRepository;
use WebAppId\Content\Tests\TestCase;
use WebAppId\Content\Repositories\ContentRepository;
use Illuminate\Support\Facades\DB;

class ContentChildTest extends TestCase
{

    private $objContentChild;
    private $resultContent;
    private $userId = '1';

    private $contentChild;
    private $contentTest;
    private $content;

    private $resultContentChild;
    private $resultContentParent;


    public function createDummyContent(){
        $this->contentTest->setUp();
        return $this->contentTest->createContent();
    }

    public function start()
    {
        $this->objContentChild = new \StdClass;
        $this->contentChild = new ContentChildRepository;
        $this->content = new ContentRepository;
        $this->contentTest = new ContentTest;
    }

    public function setUp()
    {
        parent::setUp();
        $this->start();
    }

    public function addContentChild(){
        $this->resultContentParent = $this->createDummyContent();
        $this->resultContentChild = $this->createDummyContent();
        $this->objContentChild->content_parent_id = $this->resultContentParent->id;
        $this->objContentChild->content_child_id = $this->resultContentChild->id;
        $this->objContentChild->user_id = $this->userId;
        return $this->objContentChild;
    }

    public function testContentChild(){
        $this->addContentChild();
        $result = $this->contentChild->addContentChild($this->objContentChild);
        if($result!=false){
            $this->assertTrue(true);
        }else{
            $this->assertTrue(false);
        }
    }

    public function testGetContentChild(){
        $this->addContentChild();
        $result = $this->contentChild->addContentChild($this->objContentChild);
        if($result==false){
            $this->assertTrue(false);
        }else{
            $parent = $this->content->get();            
            $child = $parent[0]->child;

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

    public function testGetContentParent(){
        $this->addContentChild();
        $result = $this->contentChild->addContentChild($this->objContentChild);
        if($result==false){
            $this->assertTrue(false);
        }else{
            $child = $this->content->get();            
            $parent = $child[1]->parent;

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

    public function testDeleteContentChildById(){
        $this->addContentChild();
        $result = $this->contentChild->addContentChild($this->objContentChild);
        if($result==false){
            $this->assertTrue(false);
        }else{
            $resultDelete = $this->contentChild->deleteContentChild($result->id);
            if($resultDelete){
                $this->assertTrue(true);
            }else{
                $this->assertTrue(false);
            }
        }
    }

    public function testDeleteContentChildByParentId(){
        $this->addContentChild();
        $result = $this->contentChild->addContentChild($this->objContentChild);
        if($result==false){
            $this->assertTrue(false);
        }else{
            $resultDelete = $this->contentChild->deleteContentChild($this->resultContentParent->id);
            if($resultDelete){
                $this->assertTrue(true);
            }else{
                $this->assertTrue(false);
            }
        }
    }
}
