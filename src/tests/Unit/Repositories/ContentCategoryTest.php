<?php

namespace WebAppId\Content\Tests\Unit\Repositories;

use WebAppId\Content\Models\Category;
use WebAppId\Content\Models\Content;
use WebAppId\Content\Models\ContentCategory;
use WebAppId\Content\Repositories\ContentCategoryRepository;
use WebAppId\Content\Services\Params\AddContentCategoryParam;
use WebAppId\Content\Tests\TestCase;

class ContentCategoryTest extends TestCase
{
    
    private $resultContent;
    private $resultCategory;
    
    private $contentCategoryRepository;
    private $contentTest;
    private $categoryTest;
    
    
    private function getContentTest(): ContentTest
    {
        if ($this->contentTest == null) {
            $this->contentTest = new ContentTest;
        }
        
        return $this->contentTest;
    }
    
    private function getCategoryTest(): CategoryTest
    {
        if ($this->categoryTest == null) {
            $this->categoryTest = new CategoryTest;
        }
        
        return $this->categoryTest;
    }
    
    public function contentCategoryRepository(): ContentCategoryRepository
    {
        if ($this->contentCategoryRepository == null) {
            $this->contentCategoryRepository = $this->getContainer()->make(ContentCategoryRepository::class);
        }
        return $this->contentCategoryRepository;
    }
    
    public function setUp(): void
    {
        parent::setUp();
    }
    
    
    private function createDummyContent(): ?Content
    {
        return $this->getContentTest()->createContent($this->getContentTest()->getDummy());
    }
    
    private function createDummyCategory(): ?Category
    {
        return $this->getCategoryTest()->createCategory($this->getCategoryTest()->getDummy());
    }
    
    public function getDummy(): ?AddContentCategoryParam
    {
        $this->resultContent = $this->createDummyContent();
        if (!$this->resultContent) {
            $this->assertTrue(false);
        } else {
            $this->resultCategory = $this->createDummyCategory();
            if (!$this->resultCategory) {
                $this->assertTrue(false);
            } else {
                $dummy = new AddContentCategoryParam();
                $dummy->setContentId($this->resultContent->id);
                $dummy->setCategoryId($this->resultCategory->id);
                $dummy->setUserId(1);
                return $dummy;
            }
        }
    }
    
    public function createContentCategory($dummy): ?ContentCategory
    {
        return $this->getContainer()->call([$this->contentCategoryRepository(), 'addContentCategory'], ['addContentCategoryParam' => $dummy]);
    }
    
    public function testAddContentCategory(): void
    {
        $result = $this->createContentCategory($this->getDummy());
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $this->assertTrue(true);
        }
    }
    
    public function testUpdateContentCategory(): void
    {
        $resultContentCategory = $this->createContentCategory($this->getDummy());
        
        if (!$resultContentCategory) {
            $this->assertTrue(false);
        } else {
            $result = $this->createDummyCategory();
            $dummy = new AddContentCategoryParam();
            $dummy->setContentId($this->resultContent->id);
            $dummy->setCategoryId($result->id);
            $dummy->setUserId(1);
            $result = $this->getContainer()->call([$this->contentCategoryRepository(), 'updateContentCategory'], ['addContentCategoryParam' => $dummy, 'id' => $resultContentCategory->id]);
            if ($result) {
                $this->assertTrue(true);
            } else {
                $this->assertTrue(false);
            }
        }
    }
    
    public function testDeleteContentCategoryById(): void
    {
        $result = $this->createContentCategory($this->getDummy());
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $result = $this->getContainer()->call([$this->contentCategoryRepository(), 'deleteContentCategory'], ['id' => $result->id]);
            if ($result) {
                $this->assertTrue(true);
            } else {
                $this->assertTrue(false);
            }
        }
    }
    
    public function testContentCategoryGetAll(): void
    {
        $dummy = $this->getDummy();
        $result = $this->createContentCategory($dummy);
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $this->assertEquals($dummy->getContentId(), $result->content_id);
            $this->assertEquals($dummy->getCategoryId(), $result->categories_id);
            $result = $this->getContainer()->call([$this->contentCategoryRepository(), 'getAll']);
            if (count($result) > 0) {
                $this->assertTrue(true);
                $this->assertEquals($dummy->getContentId(), $result[count($result) - 1]->content_id);
                $this->assertEquals($dummy->getCategoryId(), $result[count($result) - 1]->categories_id);
            } else {
                $this->assertTrue(false);
            }
        }
    }
    
    public function testContentCategories(): void
    {
        $result = $this->createContentCategory($this->getDummy());
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $this->assertTrue(true);
        }
    }
}
