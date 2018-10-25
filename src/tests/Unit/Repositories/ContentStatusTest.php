<?php

namespace WebAppId\Content\Tests\Unit\Repositories;

use WebAppId\Content\Repositories\ContentStatusRepository;
use WebAppId\Content\Tests\TestCase;

use Illuminate\Container\Container;

class ContentStatusTest extends TestCase
{
    private $container;

    private $contentStatus;

    public function start(){
        
        $this->container = new Container;

        $this->contentStatus = $this->container->make(ContentStatusRepository::class);
    }

    public function setUp()
    {
        parent::setUp();
        $this->start();
    }

    private function getDummy()
    {
        $dummy = new \StdClass;
        $dummy->name = $this->getFaker()->word;
        $dummy->user_id = '1';
        return $dummy;
    }

    public function createContentStatus($dummy){
        return $this->container->call([$this->contentStatus,'addContentStatus'],['request'=>$dummy]); 
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAddContentStatus()
    {
        $result = $this->createContentStatus($this->getDummy());

        if (!$result) {
            $this->assertTrue(false);
        } else {
            $this->assertTrue(true);
        }
    }

    public function testGetContentStatus(){
        $result = $this->createContentStatus($this->getDummy());

        if (!$result) {
            $this->assertTrue(false);
        } else {
            $result = $this->container->call([$this->contentStatus,'getContentStatus']);
            if(count($result)>0){
                $this->assertTrue(true);
            }else{
                $this->assertTrue(false);
            }
        }
    }

    public function testUpdateContentStatus(){
        $result = $this->createContentStatus($this->getDummy());

        if (!$result) {
            $this->assertTrue(false);
        }else{
            
            $result = $this->container->call([$this->contentStatus,'updateContentStatus'],["id" => $result->id, 'request'=>$this->getDummy()]);
            if($result){
                $this->assertTrue(true);
            }else{
                $this->assertTrue(false);
            }
        }
    }

}
