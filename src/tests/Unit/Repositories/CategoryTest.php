<?php

namespace WebAppId\Content\Tests\Unit\Repositories;

use WebAppId\Content\Models\Category;
use WebAppId\Content\Repositories\CategoryRepository;
use WebAppId\Content\Tests\TestCase;

class CategoryTest extends TestCase
{
    private $categoryRepository;
    
    
    public function getDummy(): object
    {
        $objCategory = new \StdClass;
        $objCategory->code = $this->getFaker()->word;
        $objCategory->name = $this->getFaker()->word;
        $objCategory->status_id = $this->getFaker()->numberBetween(1, 2);
        $objCategory->user_id = '1';
        return $objCategory;
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
        return $this->getContainer()->call([$this->categoryRepository(), 'addCategory'], ['data' => $dummyData]);
    }
    
    public function testAddCategory(): ?object
    {
        $result = $this->createCategory($this->getDummy());
        
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $this->assertTrue(true);
            return $result;
        }
    }
    
    public function testGetCategoryByCode(): void
    {
        $dummyData = $this->getDummy();
        $result = $this->createCategory($dummyData);
        
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $result = $this->getContainer()->call([$this->categoryRepository(), 'getCategoryByCode'], ['code' => $dummyData->code]);
            if ($result != null) {
                $this->assertTrue(true);
                $this->assertEquals($dummyData->code, $result->code);
                $this->assertEquals($dummyData->name, $result->name);
                $this->assertEquals($dummyData->status_id, $result->status_id);
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
                $result = $this->getContainer()->call([$this->categoryRepository(), 'updateCategory'], ['request' => $dummyData, 'id' => $result->id]);
                if ($result) {
                    $this->assertTrue(true);
                    $this->assertEquals($dummyData->code, $result->code);
                    $this->assertEquals($dummyData->name, $result->name);
                    $this->assertEquals($dummyData->status_id, $result->status_id);
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
            if ($result) {
                $this->assertTrue(true);
            } else {
                $this->assertTrue(false);
            }
        }
    }
    
    public function testChildCategory(): void
    {
        $resultCategory = $this->testAddCategory();
        
        $dummy = $this->getDummy();
        $dummy->parent_id = $resultCategory->id;
        
        $resultChild = $this->createCategory($dummy);
        
        if ($resultChild == null) {
            self::assertTrue(false);
        } else {
            self::assertTrue(true);
            self::assertEquals($dummy->name, $resultChild->name);
            self::assertEquals($dummy->code, $resultChild->code);
            self::assertEquals($dummy->parent_id, $resultChild->parent_id);
        }
    }
}
