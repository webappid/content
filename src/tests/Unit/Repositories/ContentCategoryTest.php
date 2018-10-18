<?php

namespace WebAppId\Content\Tests\Unit\Repositories;

use WebAppId\Content\Repositories\ContentCategoryRepository;
use WebAppId\Content\Tests\TestCase;
use WebAppId\Content\Tests\Unit\Repositories\ContentTest;

use Illuminate\Container\Container;

class ContentCategoryTest extends TestCase
{

    private $objContentCategory;
    private $resultContent;
    private $resultCategory;

    private $contentCategory;
    private $contentTest;
    private $categoryTest;

    public function getObjContentCategory()
    {
        return $this->objContentCategory;
    }

    private function createDummyContent()
    {
        return $this->contentTest->createContent();
    }

    private function createDummyCategory()
    {
        return $this->categoryTest->createCategory();
    }

    public function createDummy()
    {
        $this->resultContent = $this->createDummyContent();
        if (!$this->resultContent) {
            $this->assertTrue(false);
        } else {
            $this->resultCategory = $this->createDummyCategory();
            if (!$this->resultCategory) {
                $this->assertTrue(false);
            } else {
                $this->objContentCategory->content_id = $this->resultContent->id;
                $this->objContentCategory->categories_id = $this->resultCategory->id;
                $this->objContentCategory->user_id = '1';
            }
        }
    }

    public function createContentCategory()
    {
        $this->createDummy();
        return $this->contentCategory->addContentCategory($this->objContentCategory);
    }

    public function start()
    {
        $category = new Container;
        $this->contentCategory = $category->make(ContentCategoryRepository::class);
        $this->objContentCategory = new \StdClass;
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
        $result = $this->createContentCategory();
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $this->assertTrue(true);
        }
    }

    public function testUpdateContentCategory()
    {
        $resultContentCategory = $this->createContentCategory();

        if (!$resultContentCategory) {
            $this->assertTrue(false);
        } else {
            $result = $this->createDummyCategory();
            $this->objContentCategory->content_id = $this->resultContent->id;
            $this->objContentCategory->categories_id = $result->id;
            $this->objContentCategory->user_id = '1';
            $result = $this->contentCategory->updateContentCategory($this->objContentCategory, $resultContentCategory->id);
            if ($result) {
                $this->assertTrue(true);
            } else {
                $this->assertTrue(false);
            }
        }
    }

    public function testDeleteContentCategoryById()
    {
        $result = $this->createContentCategory();
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $result = $this->contentCategory->deleteContentCategory($result->id);
            if ($result) {
                $this->assertTrue(true);
            } else {
                $this->assertTrue(false);
            }
        }
    }

    public function testContentCategoryGetAll(){
        $result = $this->createContentCategory();
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $result = $this->contentCategory->getAll();
            if(count($result)>0){
                $this->assertTrue(true);
            }else{
                $this->assertTrue(false);
            }
        }
    }

    public function testContentCategories(){
        $result = $this->createContentCategory();
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $contentData = $this->contentTest->getContent()->getCategory();
            
            $this->assertEquals($contentData[0]->name, $this->resultCategory->name);
        }
    }
}
