<?php

namespace WebAppId\Content\Tests\Unit\Repositories;

use WebAppId\Content\Repositories\CategoryRepository;
use WebAppId\Content\Tests\TestCase;

use Illuminate\Container\Container;

class CategoryTest extends TestCase
{
    private $objCategory;

    private $category;


    public function getObjCategory(){
        return $this->objCategory;
    }

    public function createDummy()
    {
        $faker = $this->getFaker();
        $this->objCategory = new \StdClass;
        $this->objCategory->code = $faker->word;
        $this->objCategory->name = $faker->word;
        $this->objCategory->user_id = '1';
        return $this->objCategory;
    }

    public function start(){
        $container = new Container;
        $this->category = $container->make(CategoryRepository::class);
    }
    
    public function setUp()
    {
        parent::setUp();
        $this->start();
    }

    public function createCategory(){
        $this->createDummy();
        return $this->category->addCategory($this->objCategory);
    }

    public function testAddCategory()
    {
        $result = $this->createCategory();
        
        if(!$result){
            $this->assertTrue(false);
        }else{
            $this->assertTrue(true);
        }
    }

    public function testGetCategoryByCode(){
        $result = $this->createCategory();

        if(!$result){
            $this->assertTrue(false);
        }else{
            $result = $this->category->getCategoryByCode($this->objCategory->code);
            if($result!=null){
                $this->assertTrue(true);
                $this->assertEquals($this->objCategory->code, $result->code);
                $this->assertEquals($this->objCategory->name, $result->name);
            }else{
                $this->assertTrue(false);
            }
        }
    }

    public function testUpdateCategory(){
        $result = $this->createCategory();

        if(!$result){
            $this->assertTrue(false);
        }else{
            $result = $this->category->getOne($result->id);
            if($result==null){
                $this->assertTrue(true);
            }else{
                $this->createDummy();
                $result = $this->category->updateCategory($this->objCategory,$result->id);
                if($result){
                    $this->assertTrue(true);
                }else{
                    $this->assertTrue(false);
                }
            }
        }
    }

    public function testDeleteCategory(){
        $result = $this->createCategory();
        
        if(!$result){
            $this->assertTrue(false);
        }else{
            $result = $this->category->deleteCategory($result->id);
            if($result){
                $this->assertTrue(true);
            }else{
                $this->assertTrue(false);
            }
        }
    }
}
