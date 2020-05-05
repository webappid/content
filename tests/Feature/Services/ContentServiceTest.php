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

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        try {
            $this->contentService = $this->container->make(ContentService::class);
            $this->fileRepositoryTest = $this->container->make(FileRepositoryTest::class);
            $this->contentRepositoryTest = $this->container->make(ContentRepositoryTest::class);
            $this->contentCategoryRepositoryTest = $this->container->make(ContentCategoryRepositoryTest::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }
    }

    public function testStore()
    {
        $content = $this->container->call([$this->contentRepositoryTest, 'getDummy']);
        $contentServiceResponse = null;
        try {
            $contentServiceRequest = $this->container->make(ContentServiceRequest::class);
            $file = $this->container->call([$this->fileRepositoryTest, 'testStore']);
            $categories = $this->container->call([$this->contentCategoryRepositoryTest, 'testStore']);
            $contentServiceRequest = Lazy::copy($content, $contentServiceRequest);
            $contentServiceRequest->galleries = [$file->id];
            $contentServiceRequest->categories = [$categories->id];
            $contentServiceResponse = $this->container->call([$this->contentService, 'store'], compact('contentServiceRequest'));
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
            $contentServiceSearchRequest = $this->container->make(ContentServiceSearchRequest::class);
            $contentServiceSearchRequest->q = $content->content->title;

            $result = $this->container->call([$this->contentService, 'get'], compact('contentServiceSearchRequest'));
            self::assertTrue($result->status);
        } catch (BindingResolutionException $e) {
            report($e);
        }
    }

    public function testDetail()
    {
        $content = $this->testStore();
        $contentResult = $this->container->call([$this->contentService, 'detail'], ['code' => $content->content->code]);
        self::assertTrue($contentResult->status);
    }

    public function testUpdate()
    {
        $contentResponse = $this->testStore();
        $newContent = $this->container->call([$this->contentRepositoryTest, 'getDummy']);
        try {
            $contentServiceRequest = $this->container->make(ContentServiceRequest::class);
            $contentServiceRequest = Lazy::copy($newContent, $contentServiceRequest);
            $file = $this->container->call([$this->fileRepositoryTest, 'testStore']);
            $categories = $this->container->call([$this->contentCategoryRepositoryTest, 'testStore']);
            $contentServiceRequest->galleries = [$file->id];
            $contentServiceRequest->categories = [$categories->id];
            $result = $this->container->call([$this->contentService, 'update'], ['code' => $contentResponse->content->code, 'contentServiceRequest' => $contentServiceRequest]);
            self::assertTrue($result->status);
        } catch (BindingResolutionException $e) {
            report($e);
        }

    }

    public function testUpdateStatus()
    {
        $contentResponse = $this->testStore();
        try {
            $contentStatusRepositoryTest = $this->container->make(ContentStatusRepositoryTest::class);
            $contentStatus = $this->container->call([$contentStatusRepositoryTest, 'testStore']);
            $result = $this->container->call([$this->contentService, 'updateStatusByCode'], ['code' => $contentResponse->content->code, 'status' => $contentStatus->id]);
            self::assertTrue($result->status);
        } catch (BindingResolutionException $e) {
            report($e);
        }
    }
}
