<?php

namespace WebAppId\Content\Tests\Unit\Repositories;

use WebAppId\Content\Repositories\CategoryRepository;
use WebAppId\Content\Tests\TestCase;

class CategoryTest extends TestCase
{
    private $categoryRepository;
    
    
    public function getDummy()
    {
        $objCategory = new \StdClass;
        $objCategory->code = $this->getFaker()->word;
        $objCategory->name = $this->getFaker()->word;
        $objCategory->status_id = $this->getFaker()->numberBetween(1, 2);
        $objCategory->user_id = '1';
        return $objCategory;
    }
    
    public function start()
    {
        $this->categoryRepository = $this->getContainer()->make(CategoryRepository::class);
    }
    
    public function setUp()
    {
        parent::setUp();
        $this->start();
    }
    
    public function createCategory($dummyData)
    {
        return $this->getContainer()->call([$this->categoryRepository, 'addCategory'], ['data' => $dummyData]);
    }
    
    public function testAddCategory()
    {
        $result = $this->createCategory($this->getDummy());
    
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $this->assertTrue(true);
            return $result;
        }
    }
    
    public function testGetCategoryByCode()
    {
        $dummyData = $this->getDummy();
        $result = $this->createCategory($dummyData);
        
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $result = $this->getContainer()->call([$this->categoryRepository, 'getCategoryByCode'], ['code' => $dummyData->code]);
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
    
    public function testUpdateCategory()
    {
        $dummyData = $this->getDummy();
        $result = $this->createCategory($dummyData);
        
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $result = $this->getContainer()->call([$this->categoryRepository, 'getOne'], ['id' => $result->id]);
            if ($result == null) {
                $this->assertTrue(true);
            } else {
                $dummyData = $this->getDummy();
                $result = $this->getContainer()->call([$this->categoryRepository, 'updateCategory'], ['data' => $dummyData, 'id' => $result->id]);
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
    
    public function testDeleteCategory()
    {
        $result = $this->createCategory($this->getDummy());
        
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $result = $this->getContainer()->call([$this->categoryRepository, 'deleteCategory'], ['id' => $result->id]);
            if ($result) {
                $this->assertTrue(true);
            } else {
                $this->assertTrue(false);
            }
        }
    }
    
    public function testChildCategory()
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
