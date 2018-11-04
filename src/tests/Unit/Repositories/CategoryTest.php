<?php

namespace WebAppId\Content\Tests\Unit\Repositories;

use WebAppId\Content\Repositories\CategoryRepository;
use WebAppId\Content\Tests\TestCase;

use Illuminate\Container\Container;

class CategoryTest extends TestCase
{
    private $category;

    private $container;

    
    public function getDummy()
    {
        $faker = $this->getFaker();
        $objCategory = new \StdClass;
        $objCategory->code = $faker->word;
        $objCategory->name = $faker->word;
        $objCategory->status_id = $faker->numberBetween(1,2);
        $objCategory->user_id = '1';
        return $objCategory;
    }

    public function start(){
        $this->container = new Container;
        $this->category = $this->container->make(CategoryRepository::class);
    }
    
    public function setUp()
    {
        parent::setUp();
        $this->start();
    }

    public function createCategory($dummyData){ 
        return $this->container->call([$this->category,'addCategory'],['data' => $dummyData]);
    }

    public function testAddCategory()
    {
        $result = $this->createCategory($this->getDummy());
        
        if(!$result){
            $this->assertTrue(false);
        }else{
            $this->assertTrue(true);
        }
    }

    public function testGetCategoryByCode(){
        $dummyData = $this->getDummy();
        $result = $this->createCategory($dummyData);

        if(!$result){
            $this->assertTrue(false);
        }else{
            $result = $this->container->call([$this->category,'getCategoryByCode'],['code' => $dummyData->code]);
            if($result!=null){
                $this->assertTrue(true);
                $this->assertEquals($dummyData->code, $result->code);
                $this->assertEquals($dummyData->name, $result->name);
                $this->assertEquals($dummyData->status_id, $result->status_id);
            }else{
                $this->assertTrue(false);
            }
        }
    }

    public function testUpdateCategory(){
        $dummyData = $this->getDummy();
        $result = $this->createCategory($dummyData);

        if(!$result){
            $this->assertTrue(false);
        }else{
            $result = $this->container->call([$this->category,'getOne'],['id' => $result->id]);
            if($result==null){
                $this->assertTrue(true);
            }else{
                $dummyData = $this->getDummy();
                $result = $this->container->call([$this->category,'updateCategory'],['data' => $dummyData, 'id' => $result->id]);
                if($result){
                    $this->assertTrue(true);
                    $this->assertEquals($dummyData->code, $result->code);
                    $this->assertEquals($dummyData->name, $result->name);
                    $this->assertEquals($dummyData->status_id, $result->status_id);
                }else{
                    $this->assertTrue(false);
                }
            }
        }
    }

    public function testDeleteCategory(){
        $result = $this->createCategory($this->getDummy());
        
        if(!$result){
            $this->assertTrue(false);
        }else{
            $result = $this->container->call([$this->category,'deleteCategory'],['id' => $result->id]);
            if($result){
                $this->assertTrue(true);
            }else{
                $this->assertTrue(false);
            }
        }
    }
}
