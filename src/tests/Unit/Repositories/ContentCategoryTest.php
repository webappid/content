<?php

namespace WebAppId\Content\Tests\Unit\Repositories;

use WebAppId\Content\Repositories\ContentCategoryRepository;
use WebAppId\Content\Tests\TestCase;

use Illuminate\Container\Container;

class ContentCategoryTest extends TestCase
{

    private $resultContent;
    private $resultCategory;

    private $contentCategory;
    private $contentTest;
    private $categoryTest;

    private $container;

    private function createDummyContent()
    {
        return $this->contentTest->createContent($this->contentTest->getDummy());
    }

    private function createDummyCategory()
    {
        return $this->categoryTest->createCategory($this->categoryTest->getDummy());
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
        return $this->container->call([$this->contentCategory,'addContentCategory'],['data' => $dummy]);
    }

    public function start()
    {
        $this->container = new Container;
        $this->contentCategory = $this->container->make(ContentCategoryRepository::class);
        
        $this->contentTest = new ContentTest;
        $this->contentTest->setUp();
        $this->categoryTest = new CategoryTest;
        $this->categoryTest->setUp();
    }

    public function setUp()
    {
        parent::setUp();
        $this->start();
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
            $result = $this->container->call([$this->contentCategory,'updateContentCategory'],['data' => $dummy, 'id' => $resultContentCategory->id]);
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
            $result = $this->container->call([$this->contentCategory,'deleteContentCategory'],['id' => $result->id]);
            if ($result) {
                $this->assertTrue(true);
            } else {
                $this->assertTrue(false);
            }
        }
    }

    public function testContentCategoryGetAll(){
        $dummy = $this->getDummy();
        $result = $this->createContentCategory($dummy);
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $this->assertEquals($dummy->content_id , $result->content_id);
            $this->assertEquals($dummy->categories_id , $result->categories_id);
            $result = $this->container->call([$this->contentCategory,'getAll']);
            if(count($result)>0){
                $this->assertTrue(true);
                $this->assertEquals($dummy->content_id , $result[count($result)-1]->content_id);
                $this->assertEquals($dummy->categories_id , $result[count($result)-1]->categories_id);
            }else{
                $this->assertTrue(false);
            }
        }
    }

    public function testContentCategories(){
        $result = $this->createContentCategory($this->getDummy());
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $this->assertTrue(true);
        }
    }
}
