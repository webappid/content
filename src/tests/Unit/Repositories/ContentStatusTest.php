<?php

namespace WebAppId\Content\Tests\Unit\Repositories;

use WebAppId\Content\Repositories\ContentStatusRepository;
use WebAppId\Content\Tests\TestCase;

class ContentStatusTest extends TestCase
{
    
    private $contentStatusRepository;
    
    public function start()
    {
        
        $this->contentStatusRepository = $this->getContainer()->make(ContentStatusRepository::class);
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
    
    public function createContentStatus($dummy)
    {
        return $this->getContainer()->call([$this->contentStatusRepository, 'addContentStatus'], ['request' => $dummy]);
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
    
    public function testGetContentStatus()
    {
        $dummy = $this->getDummy();
        $result = $this->createContentStatus($dummy);
        
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $this->assertEquals($dummy->name, $result->name);
            $result = $this->getContainer()->call([$this->contentStatusRepository, 'getContentStatus']);
            if (count($result) > 0) {
                $this->assertTrue(true);
                $this->assertEquals($dummy->name, $result[count($result) - 1]->name);
            } else {
                $this->assertTrue(false);
            }
        }
    }
    
    public function testUpdateContentStatus()
    {
        $dummy = $this->getDummy();
        $result = $this->createContentStatus($dummy);
        
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $this->assertEquals($dummy->name, $result->name);
            $dummy = $this->getDummy();
            $result = $this->getContainer()->call([$this->contentStatusRepository, 'updateContentStatus'], ["id" => $result->id, 'request' => $dummy]);
            if ($result) {
                $this->assertEquals($dummy->name, $result->name);
                $this->assertTrue(true);
            } else {
                $this->assertTrue(false);
            }
        }
    }
    
}
