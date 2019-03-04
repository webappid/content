<?php

namespace WebAppId\Content\Tests\Unit\Repositories;

use WebAppId\Content\Models\Tag;
use WebAppId\Content\Repositories\TagRepository;
use WebAppId\Content\Services\Params\AddTagParam;
use WebAppId\Content\Tests\TestCase;

class TagTest extends TestCase
{
    private $tagRepository;
    
    
    public function getDummy(): object
    {
        $addTagParam = new AddTagParam();
        $addTagParam->setName($this->getFaker()->word);
        $addTagParam->setUserId(1);
        return $addTagParam;
    }
    
    public function createTag($dummy): ?Tag
    {
        $resultTag = $this->getContainer()->call([$this->tagRepository(), 'addTag'], ['addTagParam' => $dummy]);
        if (!$resultTag) {
            $this->assertTrue(false);
        } else {
            return $resultTag;
        }
    }
    
    private function tagRepository(): TagRepository
    {
        if ($this->tagRepository == null) {
            $this->tagRepository = $this->tagRepository = $this->getContainer()->make(TagRepository::class);
        }
        return $this->tagRepository;
    }
    
    public function testAddTag(): void
    {
        $dummy = $this->getDummy();
        $resultTag = $this->createTag($dummy);
        $this->assertEquals($dummy->getName(), $resultTag->name);
        
    }
    
    public function testGetTagByName(): void
    {
        $randomNumber = $this->getFaker()->numberBetween(0, 9);
        
        $name = '';
        
        for ($i = 0; $i < 10; $i++) {
            $dummy = $this->getDummy();
            $this->createTag($dummy);
            if ($randomNumber == $i) {
                $name = $dummy->getName();
            }
        }
        
        $resultTagSearch = $this->getContainer()->call([$this->tagRepository(), 'getTagByName'], ['name' => $name]);
        if ($resultTagSearch == null) {
            $this->assertTrue(false);
        } else {
            $this->assertTrue(true);
        }
    }
}
