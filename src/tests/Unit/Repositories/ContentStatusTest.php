<?php

namespace WebAppId\Content\Tests\Unit\Repositories;

use WebAppId\Content\Models\ContentStatus;
use WebAppId\Content\Repositories\ContentStatusRepository;
use WebAppId\Content\Tests\TestCase;

class ContentStatusTest extends TestCase
{
    
    private $contentStatusRepository;
    
    private function contentStatusRepository(): ContentStatusRepository
    {
        if ($this->contentStatusRepository == null) {
            $this->contentStatusRepository = $this->contentStatusRepository = $this->getContainer()->make(ContentStatusRepository::class);
        }
        return $this->contentStatusRepository;
    }
    
    public function setUp(): void
    {
        parent::setUp();
    }
    
    private function getDummy(): object
    {
        $dummy = new \StdClass;
        $dummy->name = $this->getFaker()->word;
        $dummy->user_id = '1';
        return $dummy;
    }
    
    public function createContentStatus($dummy): ?ContentStatus
    {
        return $this->getContainer()->call([$this->contentStatusRepository(), 'addContentStatus'], ['request' => $dummy]);
    }
    
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAddContentStatus(): void
    {
        $result = $this->createContentStatus($this->getDummy());
        
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $this->assertTrue(true);
        }
    }
    
    public function testGetContentStatus(): void
    {
        $dummy = $this->getDummy();
        $result = $this->createContentStatus($dummy);
        
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $this->assertEquals($dummy->name, $result->name);
            $result = $this->getContainer()->call([$this->contentStatusRepository(), 'getContentStatus']);
            if (count($result) > 0) {
                $this->assertTrue(true);
                $this->assertEquals($dummy->name, $result[count($result) - 1]->name);
            } else {
                $this->assertTrue(false);
            }
        }
    }
    
    public function testUpdateContentStatus(): void
    {
        $dummy = $this->getDummy();
        $result = $this->createContentStatus($dummy);
        
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $this->assertEquals($dummy->name, $result->name);
            $dummy = $this->getDummy();
            $result = $this->getContainer()->call([$this->contentStatusRepository(), 'updateContentStatus'], ["id" => $result->id, 'request' => $dummy]);
            if ($result) {
                $this->assertEquals($dummy->name, $result->name);
                $this->assertTrue(true);
            } else {
                $this->assertTrue(false);
            }
        }
    }
    
}
