<?php

namespace WebAppId\Content\Tests\Unit\Models;

use WebAppId\Content\Models\Tag;
use WebAppId\Content\Tests\TestCase;

class TagTest extends TestCase
{
    private $objTag;
    private $tag;

    public function createDummy()
    {
        $this->objTag->name = $this->faker->word;
        $this->objTag->user_id = '1';
    }

    public function createTag()
    {
        $this->createDummy();
        $resultTag = $this->tag->addTag($this->objTag);
        if (!$resultTag) {
            $this->assertTrue(false);
        } else {
            return $resultTag;
        }
    }

    public function start()
    {
        $this->tag = new Tag;
        $this->objTag = new \StdClass;
    }

    public function setUp()
    {
        parent::setUp();
        $this->start();
    }

    public function testAddTag()
    {
        $resultTag = $this->createTag();
        $this->assertEquals($this->objTag->name, $resultTag->name);
        
    }

    public function testGetTagByName(){
        $randomNumber = $this->faker->numberBetween(0,9);

        $name = '';

        for ($i=0; $i < 10; $i++) { 
            $this->start();
            $this->createTag();
            if($randomNumber==$i){
                $name = $this->objTag->name;
            }
        }
        
        $resultTagSearch = $this->tag->getTagByName($name);
        if($resultTagSearch==null){
            $this->assertTrue(false);
        }else{
            $this->assertTrue(true);
        }
    }
}
