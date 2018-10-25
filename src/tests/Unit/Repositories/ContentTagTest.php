<?php

namespace WebAppId\Content\Tests\Unit\Repositories;

use WebAppId\Content\Tests\Unit\Repositories\ContentTest;
use WebAppId\Content\Tests\Unit\Repositories\TagTest;
use WebAppId\Content\Repositories\ContentTagRepository;
use WebAppId\Content\Repositories\ContentRepository;
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
        $resultContentTag = $this->container->call([$this->contentTag,'addContentTag'],['response'=>$this->getDummy()]);
        
        if(!$resultContentTag){
            $this->assertTrue(false);
        }else{
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