<?php

namespace WebAppId\Content\Tests\Unit\Repositories;

use WebAppId\Content\Repositories\ContentTagRepository;
use WebAppId\Content\Tests\TestCase;

use Illuminate\Container\Container;

class ContentTagTest extends TestCase
{
    private $contentTest;
    private $tagTest;
    private $contentTag;
    private $resultTagTest;
    private $resultContentTest;

    private $container;

    public function setUp(){
        parent::setUp();
        $this->start();
    }

    public function start(){
        $this->contentTest = new ContentTest;
        $this->contentTest->setUp();
        $this->tagTest = new TagTest;
        $this->tagTest->setUp();
        $this->contentTag = new ContentTagRepository;
        $this->container = new Container;
    }

    public function createDummyContent(){
        return $this->contentTest->createContent($this->contentTest->getDummy());
    }

    public function createDummyTag(){
        return $this->tagTest->createTag($this->tagTest->getDummy());
    }

    public function getDummy(){
        $this->resultContentTest = $this->createDummyContent();
        $this->resultTagTest = $this->createDummyTag();

        $dummy = new \StdClass;

        $dummy->content_id = $this->resultContentTest->id;
        $dummy->tag_id = $this->resultTagTest->id;
        $dummy->user_id = '1';
        return $dummy;
    }

    public function createContentTag(){
        $dummy = $this->getDummy();
        $resultContentTag = $this->container->call([$this->contentTag,'addContentTag'],['response'=>$dummy]);
        
        if(!$resultContentTag){
            $this->assertTrue(false);
        }else{
            $this->assertEquals($dummy->content_id, $resultContentTag->content_id);
            $this->assertEquals($dummy->tag_id , $resultContentTag->tag_id);
            return $resultContentTag;
        }
    }

    public function testAddContentTag(){
        $resultContentTag = $this->createContentTag();
        if($resultContentTag!=false){
            $this->assertTrue(true);
        }
    }

    public function testCheckContentTag(){
        $resultContentTag = $this->createContentTag();
        if($resultContentTag!=false){
            $this->assertTrue(true);
            
            $this->assertEquals($this->resultContentTest->tag[0]->name, $this->resultTagTest->name);
        }
    }
}