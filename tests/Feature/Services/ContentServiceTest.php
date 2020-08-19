<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 06/11/18
 * Time: 06.56
 */

namespace WebAppId\Content\Tests\Feature\Services;

use Illuminate\Contracts\Container\BindingResolutionException;
use WebAppId\Content\Services\ContentService;
use WebAppId\Content\Services\Requests\ContentServiceRequest;
use WebAppId\Content\Services\Requests\ContentServiceSearchRequest;
use WebAppId\Content\Tests\TestCase;
use WebAppId\Content\Tests\Unit\Repositories\CategoryRepositoryTest;
use WebAppId\Content\Tests\Unit\Repositories\ContentCategoryRepositoryTest;
use WebAppId\Content\Tests\Unit\Repositories\ContentRepositoryTest;
use WebAppId\Content\Tests\Unit\Repositories\ContentStatusRepositoryTest;
use WebAppId\Content\Tests\Unit\Repositories\FileRepositoryTest;
use WebAppId\DDD\Tools\Lazy;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 26/04/20
 * Time: 16.54
 * Class ContentServiceTest
 * @package WebAppId\Content\Tests\Feature\Services
 */
class ContentServiceTest extends TestCase
{
    /**
     * @var ContentService
     */
    private $contentService;

    /**
     * @var ContentRepositoryTest
     */
    private $contentRepositoryTest;

    /**
     * @var ContentCategoryRepositoryTest
     */
    private $contentCategoryRepositoryTest;

    /**
     * @var FileRepositoryTest
     */
    private $fileRepositoryTest;

    /**
     * @var CategoryRepositoryTest
     */
    private $categoryRepositoryTest;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        try {
            $this->contentService = app()->make(ContentService::class);
            $this->fileRepositoryTest = app()->make(FileRepositoryTest::class);
            $this->contentRepositoryTest = app()->make(ContentRepositoryTest::class);
            $this->contentCategoryRepositoryTest = app()->make(ContentCategoryRepositoryTest::class);
            $this->categoryRepositoryTest = app()->make(CategoryRepositoryTest::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }
    }

    public function testStore()
    {
        $content = app()->call([$this->contentRepositoryTest, 'getDummy']);
        $contentServiceResponse = null;
        try {
            $contentServiceRequest = app()->make(ContentServiceRequest::class);
            $file = app()->call([$this->fileRepositoryTest, 'testStore']);
            $categories = app()->call([$this->contentCategoryRepositoryTest, 'testStore']);
            $contentServiceRequest = Lazy::copy($content, $contentServiceRequest);
            $contentServiceRequest->galleries = [$file->id];
            $contentServiceRequest->categories = [$categories->id];
            $contentServiceResponse = app()->call([$this->contentService, 'store'], compact('contentServiceRequest'));
            self::assertTrue($contentServiceResponse->status);
        } catch (BindingResolutionException $e) {
            report($e);
        }
        return $contentServiceResponse;
    }

    public function testStoreDuplicateTitle()
    {
        $content = app()->call([$this->contentRepositoryTest, 'getDummy']);
        $contentServiceResponse = null;
        try {
            $contentServiceRequest = app()->make(ContentServiceRequest::class);
            $file = app()->call([$this->fileRepositoryTest, 'testStore']);
            $categories = app()->call([$this->contentCategoryRepositoryTest, 'testStore']);
            $contentServiceRequest = Lazy::copy($content, $contentServiceRequest);
            $contentServiceRequest->galleries = [$file->id];
            $contentServiceRequest->categories = [$categories->id];
            $contentServiceResponse = app()->call([$this->contentService, 'store'], compact('contentServiceRequest'));

            self::assertTrue($contentServiceResponse->status);

            $newContent = app()->call([$this->contentRepositoryTest, 'getDummy']);
            $newContentServiceRequest = app()->make(ContentServiceRequest::class);
            $newFile = app()->call([$this->fileRepositoryTest, 'testStore']);
            $newCategories = app()->call([$this->contentCategoryRepositoryTest, 'testStore']);
            $newContent->code = $content->code;
            $newContentServiceRequest = Lazy::copy($newContent, $newContentServiceRequest);
            $newContentServiceRequest->galleries = [$newFile->id];
            $newContentServiceRequest->categories = [$newCategories->id];

            $contentServiceResponse = app()->call([$this->contentService, 'store'], compact('contentServiceRequest'));
            self::assertTrue($contentServiceResponse->status);
        } catch (BindingResolutionException $e) {
            report($e);
        }
        return $contentServiceResponse;
    }

    public function testGet()
    {
        $randomNumber = $this->getFaker()->numberBetween(10, 20);
        $pickNumber = $this->getFaker()->numberBetween(0, $randomNumber);

        for ($i = 0; $i <= $randomNumber; $i++) {
            if ($pickNumber == $i) {
                $content = $this->testStore();
            } else {
                $this->testStore();
            }
        }

        try {
            $contentServiceSearchRequest = app()->make(ContentServiceSearchRequest::class);
            $contentServiceSearchRequest->q = $content->content->title;

            $result = app()->call([$this->contentService, 'get'], compact('contentServiceSearchRequest'));
            self::assertTrue($result->status);
        } catch (BindingResolutionException $e) {
            report($e);
        }
    }

    public function testGetByCategories()
    {
        $randomNumber = $this->getFaker()->numberBetween(10, 20);

        $categories = [];

        for ($i = 0; $i <= $randomNumber; $i++) {
            $content = $this->testStore();
            if (isset($content->categories[0])) {
                $categories [] = $content->categories[0]->name;
            }
        }

        $string = 'aiueo';
        $q = $string[$this->getFaker()->numberBetween(0, strlen($string) - 1)];

        try {
            $contentServiceSearchRequest = app()->make(ContentServiceSearchRequest::class);
            $contentServiceSearchRequest->q = $q;

            $contentServiceSearchRequest->categories = $categories;

            $result = app()->call([$this->contentService, 'get'], compact('contentServiceSearchRequest'));
            self::assertTrue($result->status);
        } catch (BindingResolutionException $e) {
            report($e);
        }
    }

    public function testDetail()
    {
        $content = $this->testStore();
        $contentResult = app()->call([$this->contentService, 'detail'], ['code' => $content->content->code]);
        self::assertTrue($contentResult->status);
    }

    public function testUpdate()
    {
        $contentResponse = $this->testStore();
        $newContent = app()->call([$this->contentRepositoryTest, 'getDummy']);
        try {
            $contentServiceRequest = app()->make(ContentServiceRequest::class);
            $contentServiceRequest = Lazy::copy($newContent, $contentServiceRequest);
            $file = app()->call([$this->fileRepositoryTest, 'testStore']);
            $categories = app()->call([$this->contentCategoryRepositoryTest, 'testStore']);
            $contentServiceRequest->galleries = [$file->id];
            $contentServiceRequest->categories = [$categories->id];
            $contentServiceRequest->code = $contentResponse->content->code;
            $result = app()->call([$this->contentService, 'update'], ['code' => $contentResponse->content->code, 'contentServiceRequest' => $contentServiceRequest]);
            self::assertTrue($result->status);
        } catch (BindingResolutionException $e) {
            report($e);
        }

    }

    public function testUpdateStatus()
    {
        $contentResponse = $this->testStore();
        try {
            $contentStatusRepositoryTest = app()->make(ContentStatusRepositoryTest::class);
            $contentStatus = app()->call([$contentStatusRepositoryTest, 'testStore']);
            $result = app()->call([$this->contentService, 'updateStatusByCode'], ['code' => $contentResponse->content->code, 'status' => $contentStatus->id]);
            self::assertTrue($result->status);
        } catch (BindingResolutionException $e) {
            report($e);
        }
    }
}
