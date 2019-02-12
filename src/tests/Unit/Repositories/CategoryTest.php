<?php

namespace WebAppId\Content\Tests\Unit\Repositories;

use WebAppId\Content\Models\Category;
use WebAppId\Content\Repositories\CategoryRepository;
use WebAppId\Content\Services\Params\AddCategoryParam;
use WebAppId\Content\Tests\TestCase;

class CategoryTest extends TestCase
{
    private $categoryRepository;
    
    
    public function getDummy(): AddCategoryParam
    {
        $addCategoryParam = new AddCategoryParam();
        $addCategoryParam->setCode($this->getFaker()->word);
        $addCategoryParam->setName($this->getFaker()->word);
        $addCategoryParam->setStatusId($this->getFaker()->numberBetween(1, 2));
        $addCategoryParam->setUserId(1);
        return $addCategoryParam;
    }
    
    private function categoryRepository(): CategoryRepository
    {
        if ($this->categoryRepository == null) {
            $this->categoryRepository = $this->getContainer()->make(CategoryRepository::class);
        }
        return $this->categoryRepository;
    }
    
    
    public function setUp(): void
    {
        parent::setUp();
    }
    
    public function createCategory($dummyData): ?Category
    {
        return $this->getContainer()->call([$this->categoryRepository(), 'addCategory'], ['addCategoryParam' => $dummyData]);
    }
    
    public function testAddCategory(): ?Category
    {
        $result = $this->createCategory($this->getDummy());
    
        self::assertNotEquals(null, $result);
        return $result;
    }
    
    public function testGetCategoryByCode(): void
    {
        $dummyData = $this->getDummy();
        $result = $this->createCategory($dummyData);
        
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $result = $this->getContainer()->call([$this->categoryRepository(), 'getCategoryByCode'], ['code' => $dummyData->getCode()]);
            if ($result != null) {
                $this->assertTrue(true);
                $this->assertEquals($dummyData->getCode(), $result->code);
                $this->assertEquals($dummyData->getName(), $result->name);
                $this->assertEquals($dummyData->getStatusId(), $result->status_id);
            } else {
                $this->assertTrue(false);
            }
        }
    }
    
    public function testUpdateCategory(): void
    {
        $dummyData = $this->getDummy();
        $result = $this->createCategory($dummyData);
        
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $result = $this->getContainer()->call([$this->categoryRepository(), 'getOne'], ['id' => $result->id]);
            if ($result == null) {
                $this->assertTrue(true);
            } else {
                $dummyData = $this->getDummy();
                $result = $this->getContainer()->call([$this->categoryRepository(), 'updateCategory'], ['addCategoryParam' => $dummyData, 'id' => $result->id]);
                if ($result) {
                    $this->assertTrue(true);
                    $this->assertEquals($dummyData->getCode(), $result->code);
                    $this->assertEquals($dummyData->getName(), $result->name);
                    $this->assertEquals($dummyData->getStatusId(), $result->status_id);
                } else {
                    $this->assertTrue(false);
                }
            }
        }
    }
    
    public function testDeleteCategory(): void
    {
        $result = $this->createCategory($this->getDummy());
        
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $result = $this->getContainer()->call([$this->categoryRepository(), 'deleteCategory'], ['id' => $result->id]);
            self::assertEquals(true, $result);
        }
    }
    
    public function testChildCategory(): void
    {
        $resultCategory = $this->testAddCategory();
        
        $dummy = $this->getDummy();
        $dummy->setParentId($resultCategory->id);
        
        $resultChild = $this->createCategory($dummy);
        
        if ($resultChild == null) {
            self::assertTrue(false);
        } else {
            self::assertTrue(true);
            self::assertEquals($dummy->getName(), $resultChild->name);
            self::assertEquals($dummy->getCode(), $resultChild->code);
            self::assertEquals($dummy->getParentId(), $resultChild->parent_id);
        }
    }
    
    public function testGetAll(): void
    {
        $categories = $this->getContainer()->call([$this->categoryRepository(), 'getAll']);
        self::assertGreaterThanOrEqual(1, count($categories));
    }
    
    public function testGetSearch(): void
    {
        $category = $this->testAddCategory();
        $result = $this->getContainer()->call([$this->categoryRepository(), 'getSearch'], ['search' => $category->name]);
        self::assertGreaterThanOrEqual(1, count($result));
    }
    
    public function testGetSearchOne(): void
    {
        $category = $this->testAddCategory();
        $result = $this->getContainer()->call([$this->categoryRepository(), 'getSearchOne'], ['search' => $category->name]);
        self::assertNotEquals(null, $result);
    }
    
    public function testGetAllCount(): void
    {
        $this->testAddCategory();
        $result = $this->getContainer()->call([$this->categoryRepository(), 'getAllCount']);
        self::assertGreaterThanOrEqual(1, $result);
    }
    
    public function testGetSearchCount(): void
    {
        $category = $this->testAddCategory();
        $result = $this->getContainer()->call([$this->categoryRepository(), 'getSearchCount'], ['search' => $category->name]);
        self::assertGreaterThanOrEqual(1, $result);
    }
}
