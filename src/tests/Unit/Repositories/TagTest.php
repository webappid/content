<?php

namespace WebAppId\Content\Tests\Unit\Repositories;

use WebAppId\Content\Repositories\TagRepository;
use WebAppId\Content\Tests\TestCase;

class TagTest extends TestCase
{
    private $tagRepository;
    
    
    public function getDummy()
    {
        $objDummy = new \StdClass;
        $objDummy->name = $this->getFaker()->word;
        $objDummy->user_id = '1';
        return $objDummy;
    }
    
    public function createTag($dummy)
    {
        $resultTag = $this->getContainer()->call([$this->tagRepository, 'addTag'], ['request' => $dummy]);
        if (!$resultTag) {
            $this->assertTrue(false);
        } else {
            return $resultTag;
        }
    }
    
    public function setUp()
    {
        parent::setUp();
        $this->tagRepository = $this->getContainer()->make(TagRepository::class);
    }
    
    public function testAddTag()
    {
        $dummy = $this->getDummy();
        $resultTag = $this->createTag($dummy);
        $this->assertEquals($dummy->name, $resultTag->name);
        
    }
    
    public function testGetTagByName()
    {
        $randomNumber = $this->getFaker()->numberBetween(0, 9);
        
        $name = '';
        
        for ($i = 0; $i < 10; $i++) {
            $dummy = $this->getDummy();
            $this->createTag($dummy);
            if ($randomNumber == $i) {
                $name = $dummy->name;
            }
        }
        
        $resultTagSearch = $this->getContainer()->call([$this->tagRepository, 'getTagByName'], ['name' => $name]);
        if ($resultTagSearch == null) {
            $this->assertTrue(false);
        } else {
            $this->assertTrue(true);
        }
    }
}
