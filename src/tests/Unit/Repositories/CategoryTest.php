<?php

namespace WebAppId\Content\Tests\Unit\Repositories;

use WebAppId\Content\Repositories\CategoryRepository;
use WebAppId\Content\Tests\TestCase;

use Illuminate\Container\Container;

class CategoryTest extends TestCase
{
    private $category;

    private $container;


    public function getObjCategory(){
        return $this->objCategory;
    }

    public function getDummy()
    {
        $faker = $this->getFaker();
        $this->objCategory = new \StdClass;
        $this->objCategory->code = $faker->word;
        $this->objCategory->name = $faker->word;
        $this->objCategory->user_id = '1';
        return $this->objCategory;
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
                $result = $this->container->call([$this->category,'updateCategory'],['data' => $this->getDummy(), 'id' => $result->id]);
                if($result){
                    $this->assertTrue(true);
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
