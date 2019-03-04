<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 06/11/18
 * Time: 06.56
 */

namespace WebAppId\Content\Tests\Feature\Services;

use WebAppId\Content\Repositories\ContentCategoryRepository;
use WebAppId\Content\Services\ContentService;
use WebAppId\Content\Services\Params\AddContentCategoryParam;
use WebAppId\Content\Services\Params\ContentSearchParam;
use WebAppId\Content\Tests\TestCase;
use WebAppId\Content\Tests\Unit\Repositories\ContentTest;

class ContentServiceTest extends TestCase
{
    private $contentService;
    private $contentRepositoryTest;
    private $contentCategoryRepository;
    
    public function contentService(): ContentService
    {
        if ($this->contentService == null) {
            $this->contentService = $this->getContainer()->make(ContentService::class);
        }
        return $this->contentService;
    }
    
    public function contentRepositoryTest(): ContentTest
    {
        if ($this->contentRepositoryTest == null) {
            $this->contentRepositoryTest = $this->getContainer()->make(ContentTest::class);
        }
    
        return $this->contentRepositoryTest;
    }
    
    public function contentCategoryRepository(): ContentCategoryRepository
    {
        if ($this->contentCategoryRepository == null) {
            $this->contentCategoryRepository = $this->getContainer()->make(ContentCategoryRepository::class);
        }
        return $this->contentCategoryRepository;
    }
    
    public function testAddContent()
    {
        $dummy = $this->contentRepositoryTest()->getDummy();
        $dummy->setStatusId($this->getFaker()->numberBetween(1, 4));
        $categories = [];
        $categories[] = $this->getFaker()->numberBetween(1, 4);
        $dummy->setCategories($categories);
        
        $galleries = [];
        $galleries[] = $this->getFaker()->numberBetween(1, 4);
        $dummy->setGalleries($galleries);
        
        $result = $this->getContainer()->call([$this->contentService(), 'store'], ['addContentParam' => $dummy]);
        self::assertEquals(true, $result->getStatus());
        return $dummy;
    }
    
    public function testContentSearchByCode()
    {
        $data = $this->testAddContent();
        $result = $this->getContainer()->call([$this->contentService(), 'detail'], ['code' => $data->getCode()]);
        self::assertEquals(true, $result->getStatus());
    }
    
    public function testChildContent()
    {
        $data = $this->testAddContent();
        
        $dummy = $this->contentRepositoryTest()->getDummy();
        $dummy->setStatusId($this->getFaker()->numberBetween(1, 4));
        $categories = [];
        $categories[] = $this->getFaker()->numberBetween(1, 4);
        $dummy->setCategories($categories);
        
        $galleries = [];
        $galleries[] = $this->getFaker()->numberBetween(1, 4);
        $dummy->setGalleries($galleries);
        
        $parentCode = $data->getCode();
        $parent = $this->getContainer()->call([$this->contentService(), 'detail'], ['code' => $parentCode]);
        
        $dummy->setParentId($parent->getContent()->id);
        $this->getContainer()->call([$this->contentService(), 'store'], ['addContentParam' => $dummy]);
        
        $parentChild = $this->getContainer()->call([$this->contentService(), 'detail'], ['code' => $parentCode]);
        
        self::assertGreaterThanOrEqual(1, count($parentChild->getChild()));
    }
    
    public function testUpdateContentStatus(): void
    {
        $result = $this->contentRepositoryTest()->testAddContent();
        $status_id = $this->getFaker()->numberBetween(1, 4);
        $resultUpdateStatus = $this->getContainer()->call([$this->contentService(), 'updateContentStatusByCode'], ['code' => $result->code, 'status' => $status_id]);
        
        if ($resultUpdateStatus == null) {
            self::assertTrue(false);
        } else {
            self::assertTrue(true);
            self::assertEquals($status_id, $resultUpdateStatus->status_id);
        }
    }
    
    public function testPagingContent(): void
    {
        $contentRepositoryTest = $this->contentRepositoryTest();
        
        $paging = 12;
    
        for ($i = 0; $i < $paging + 10; $i++) {
            $result = $contentRepositoryTest->testAddContent();
            $categories = [];
            $categories[0] = '1';
        
            $contentCategoryData = new AddContentCategoryParam();
            $contentCategoryData->setUserId(1);
            $contentCategoryData->setContentId($result->id);
            $contentCategoryData->setCategoryId($categories[0]);
        
            $result['content_category'] = $this->getContainer()->call([$this->contentCategoryRepository(), 'addContentCategory'], ['addContentCategoryParam' => $contentCategoryData]);
        }
    
        $request = new ContentSearchParam();
        $request->setQ('');
        $request->setCategory('page');
    
        $result = $this->getContainer()->call([$this->contentService(), 'showPaginate'], ['paginate' => $paging, 'contentSearchParam' => $request]);
        
        self::assertCount($paging, $result);
    }
    
    public function testDestroy()
    {
        $data = $this->testAddContent();
        $result = $this->getContainer()->call([$this->contentService(), 'destroy'], ['code' => $data->getCode()]);
        self::assertTrue($result);
    }
    
    public function testUpdateContentByCode()
    {
        $data = $this->testAddContent();
        
        $dummy = $this->contentRepositoryTest()->getDummy();
        $dummy->setStatusId($this->getFaker()->numberBetween(1, 4));
        $categories = [];
        $categories[] = $this->getFaker()->numberBetween(1, 4);
        $dummy->setCategories($categories);
        
        $galleries = [];
        $galleries[] = $this->getFaker()->numberBetween(1, 4);
        $dummy->setGalleries($galleries);
        
        $result = $this->getContainer()->call([$this->contentService(), 'update'], ['code' => $data->getCode(), 'addContentParam' => $dummy]);
        
        self::assertNotEquals(null, $result);
    }
}