<?php

namespace WebAppId\Content\Tests\Unit\Repositories;

use WebAppId\Content\Repositories\ContentCategoryRepository;
use WebAppId\Content\Tests\TestCase;

class ContentCategoryTest extends TestCase
{
    
    private $resultContent;
    private $resultCategory;
    
    private $contentCategoryRepository;
    private $contentTest;
    private $categoryTest;
    
    
    private function getContentTest()
    {
        if ($this->contentTest == null) {
            $this->contentTest = new ContentTest;
        }
        
        return $this->contentTest;
    }
    
    private function getCategoryTest()
    {
        if ($this->categoryTest == null) {
            $this->categoryTest = new CategoryTest;
        }
        
        return $this->categoryTest;
    }
    
    public function setUp()
    {
        parent::setUp();
        $this->contentCategoryRepository = $this->getContainer()->make(ContentCategoryRepository::class);
        $this->getContentTest()->setUp();
        $this->getCategoryTest()->setUp();
    }
    
    
    private function createDummyContent()
    {
        return $this->getContentTest()->createContent($this->getContentTest()->getDummy());
    }
    
    private function createDummyCategory()
    {
        return $this->getCategoryTest()->createCategory($this->getCategoryTest()->getDummy());
    }
    
    public function getDummy()
    {
        $this->resultContent = $this->createDummyContent();
        if (!$this->resultContent) {
            $this->assertTrue(false);
        } else {
            $this->resultCategory = $this->createDummyCategory();
            if (!$this->resultCategory) {
                $this->assertTrue(false);
            } else {
                $dummy = new \StdClass;
                $dummy->content_id = $this->resultContent->id;
                $dummy->categories_id = $this->resultCategory->id;
                $dummy->user_id = '1';
                return $dummy;
            }
        }
    }
    
    public function createContentCategory($dummy)
    {
        return $this->getContainer()->call([$this->contentCategoryRepository, 'addContentCategory'], ['data' => $dummy]);
    }
    
    public function testAddContentCategory()
    {
        $result = $this->createContentCategory($this->getDummy());
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $this->assertTrue(true);
        }
    }
    
    public function testUpdateContentCategory()
    {
        $resultContentCategory = $this->createContentCategory($this->getDummy());
    
        if (!$resultContentCategory) {
            $this->assertTrue(false);
        } else {
            $result = $this->createDummyCategory();
            $dummy = new \StdClass;
            $dummy->content_id = $this->resultContent->id;
            $dummy->categories_id = $result->id;
            $dummy->user_id = '1';
            $result = $this->getContainer()->call([$this->contentCategoryRepository, 'updateContentCategory'], ['data' => $dummy, 'id' => $resultContentCategory->id]);
            if ($result) {
                $this->assertTrue(true);
            } else {
                $this->assertTrue(false);
            }
        }
    }
    
    public function testDeleteContentCategoryById()
    {
        $result = $this->createContentCategory($this->getDummy());
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $result = $this->getContainer()->call([$this->contentCategoryRepository, 'deleteContentCategory'], ['id' => $result->id]);
            if ($result) {
                $this->assertTrue(true);
            } else {
                $this->assertTrue(false);
            }
        }
    }
    
    public function testContentCategoryGetAll()
    {
        $dummy = $this->getDummy();
        $result = $this->createContentCategory($dummy);
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $this->assertEquals($dummy->content_id, $result->content_id);
            $this->assertEquals($dummy->categories_id, $result->categories_id);
            $result = $this->getContainer()->call([$this->contentCategoryRepository, 'getAll']);
            if (count($result) > 0) {
                $this->assertTrue(true);
                $this->assertEquals($dummy->content_id, $result[count($result) - 1]->content_id);
                $this->assertEquals($dummy->categories_id, $result[count($result) - 1]->categories_id);
            } else {
                $this->assertTrue(false);
            }
        }
    }
    
    public function testContentCategories()
    {
        $result = $this->createContentCategory($this->getDummy());
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $this->assertTrue(true);
        }
    }
}
