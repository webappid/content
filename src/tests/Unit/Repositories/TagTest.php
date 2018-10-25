<?php

namespace WebAppId\Content\Tests\Unit\Repositories;

use WebAppId\Content\Repositories\TagRepository;
use WebAppId\Content\Tests\TestCase;

use Illuminate\Container\Container;

class TagTest extends TestCase
{
    private $tag;

    private $container;


    public function getDummy()
    {
        $faker = $this->getFaker();
        $objDummy = new \StdClass;
        $objDummy->name = $faker->word;
        $objDummy->user_id = '1';
        return $objDummy;
    }

    public function createTag($dummy)
    {
        $resultTag = $this->container->call([$this->tag,'addTag'],['request'=>$dummy]);
        if (!$resultTag) {
            $this->assertTrue(false);
        } else {
            return $resultTag;
        }
    }

    public function start()
    {
        $this->container = new Container;
        $this->tag = $this->container->make(TagRepository::class);
    }

    public function setUp()
    {
        parent::setUp();
        $this->start();
    }

    public function testAddTag()
    {
        $dummy = $this->getDummy();
        $resultTag = $this->createTag($dummy);
        $this->assertEquals($dummy->name, $resultTag->name);
        
    }

    public function testGetTagByName(){
        $randomNumber = $this->getFaker()->numberBetween(0,9);

        $name = '';
        

        for ($i=0; $i < 10; $i++) { 
            $dummy = $this->getDummy();
            $this->createTag($dummy);
            if($randomNumber==$i){
                $name = $dummy->name;
            }
        }
        
        $resultTagSearch = $this->container->call([$this->tag,'getTagByName'],['name'=>$name]);
        if($resultTagSearch==null){
            $this->assertTrue(false);
        }else{
            $this->assertTrue(true);
        }
    }
}
