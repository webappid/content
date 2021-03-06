<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Tests\Unit\Repositories;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Str;
use WebAppId\Content\Models\Content;
use WebAppId\Content\Repositories\ContentCategoryRepository;
use WebAppId\Content\Repositories\ContentRepository;
use WebAppId\Content\Repositories\Requests\ContentCategoryRepositoryRequest;
use WebAppId\Content\Repositories\Requests\ContentRepositoryRequest;
use WebAppId\Content\Tests\TestCase;
use WebAppId\User\Tests\Unit\Repositories\UserRepositoryTest;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 19:05:57
 * Time: 2020/04/22
 * Class ContentServiceResponseList
 * @package WebAppId\Content\Tests\Unit\Repositories
 */
class ContentRepositoryTest extends TestCase
{
    /**
     * @var ContentRepository
     */
    private $contentRepository;

    /**
     * @var ContentStatusRepositoryTest
     */
    private $contentStatusRepositoryTest;

    /**
     * @var LanguageRepositoryTest
     */
    private $languageRepositoryTest;

    /**
     * @var TimeZoneRepositoryTest
     */
    private $timezoneRepositoryTest;

    /**
     * @var UserRepositoryTest
     */
    private $userRepositoryTest;

    /**
     * @var FileRepositoryTest
     */
    private $fileRepositoryTest;

    /**
     * @var ContentCategoryRepository
     */
    private $contentCategoryRepository;

    /**
     * @var CategoryRepositoryTest
     */
    private $categoryRepositoryTest;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        try {
            $this->contentRepository = app()->make(ContentRepository::class);
            $this->userRepositoryTest = app()->make(UserRepositoryTest::class);
            $this->contentStatusRepositoryTest = app()->make(ContentStatusRepositoryTest::class);
            $this->languageRepositoryTest = app()->make(LanguageRepositoryTest::class);
            $this->timezoneRepositoryTest = app()->make(TimeZoneRepositoryTest::class);
            $this->fileRepositoryTest = app()->make(FileRepositoryTest::class);
            $this->contentCategoryRepository = app()->make(ContentCategoryRepository::class);
            $this->categoryRepositoryTest = app()->make(CategoryRepositoryTest::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }
    }

    public function getDummy(int $no = 0): ?ContentRepositoryRequest
    {
        $dummy = null;
        try {
            $dummy = app()->make(ContentRepositoryRequest::class);
            $file = app()->call([$this->fileRepositoryTest, 'testStore']);
            $contentStatus = app()->call([$this->contentStatusRepositoryTest, 'testStore']);
            $language = app()->call([$this->languageRepositoryTest, 'testStore']);
            $timeZone = app()->call([$this->timezoneRepositoryTest, 'testStore']);
            $user = app()->call([$this->userRepositoryTest, 'testStore']);

            $dummy->title = $this->getFaker()->text(100);
            $dummy->code = Str::slug($dummy->title);
            $dummy->description = $this->getFaker()->text(191);
            $dummy->keyword = $this->getFaker()->text(100);
            $dummy->og_title = $this->getFaker()->text(100);
            $dummy->og_description = $this->getFaker()->text(191);
            $dummy->default_image = $file->id;
            $dummy->status_id = $contentStatus->id;
            $dummy->language_id = $language->id;
            $dummy->time_zone_id = $timeZone->id;
            $dummy->publish_date = $this->getFaker()->date('Y-m-d H:i:s');
            $dummy->additional_info = $this->getFaker()->text(191);
            $dummy->content = $this->getFaker()->text(191);
            $dummy->creator_id = $user->id;
            $dummy->owner_id = $user->id;
            $dummy->user_id = $user->id;

        } catch (BindingResolutionException $e) {
            report($e);
        }
        return $dummy;
    }

    public function testStore(int $no = 0): ?Content
    {
        $contentRepositoryRequest = $this->getDummy($no);
        $content = app()->call([$this->contentRepository, 'store'], ['contentRepositoryRequest' => $contentRepositoryRequest]);
        $category = app()->call([$this->categoryRepositoryTest, 'testStore']);
        $contentCategoryRepositoryRequest = app()->make(ContentCategoryRepositoryRequest::class);
        $contentCategoryRepositoryRequest->content_id = $content->id;
        $contentCategoryRepositoryRequest->category_id = $category->id;
        $contentCategoryRepositoryRequest->user_id = $contentRepositoryRequest->user_id;
        app()->call([$this->contentCategoryRepository, 'store'], compact('contentCategoryRepositoryRequest'));
        self::assertNotEquals(null, $content);
        return $content;
    }

    public function testGetById()
    {
        $content = $this->testStore();
        $result = app()->call([$this->contentRepository, 'getById'], ['id' => $content->id]);
        self::assertNotEquals(null, $result);
    }

    public function testGetByCode()
    {
        $content = $this->testStore();
        $result = app()->call([$this->contentRepository, 'getByCode'], ['code' => $content->code]);
        self::assertNotEquals(null, $result);
    }

    public function testDelete()
    {
        $content = $this->testStore();
        $result = app()->call([$this->contentRepository, 'delete'], ['code' => $content->code]);
        self::assertTrue($result);
    }

    public function testGet()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(10, 30)); $i++) {
            $this->testStore($i);
        }

        $resultList = app()->call([$this->contentRepository, 'get']);
        self::assertGreaterThanOrEqual(1, count($resultList));
    }

    public function testGetCount()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(10, 30)); $i++) {
            $this->testStore($i);
        }

        $result = app()->call([$this->contentRepository, 'getCount']);

        self::assertGreaterThanOrEqual(1, $result);
    }

    public function testUpdate()
    {
        $content = $this->testStore();
        $contentRepositoryRequest = $this->getDummy(1);
        $result = app()->call([$this->contentRepository, 'update'], ['code' => $content->code, 'contentRepositoryRequest' => $contentRepositoryRequest]);
        self::assertNotEquals(null, $result);
    }

    public function testGetWhere()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(10, 30)); $i++) {
            $this->testStore($i);
        }
        $string = 'aiueo';
        $q = $string[$this->getFaker()->numberBetween(0, strlen($string) - 1)];
        $result = app()->call([$this->contentRepository, 'get'], ['q' => $q]);
        self::assertGreaterThanOrEqual(1, count($result));
    }

    public function testGetWhereCount()
    {
        $randomNumber = $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(30, 70));
        for ($i = 0; $i < $randomNumber; $i++) {
            $this->testStore($i);
        }
        $string = 'aiueo';
        $q = $string[$this->getFaker()->numberBetween(0, strlen($string) - 1)];
        $result = app()->call([$this->contentRepository, 'getCount'], ['q' => $q]);
        self::assertGreaterThanOrEqual(1, $result);
    }

    public function testGetDuplicateTitle()
    {
        $q = $this->getFaker()->userName;
        $contentRepositoryRequest = $this->getDummy();
        $contentRepositoryRequest->code = $q;

        app()->call([$this->contentRepository, 'store'], ['contentRepositoryRequest' => $contentRepositoryRequest]);

        $contentRepositoryRequest = $this->getDummy();
        $contentRepositoryRequest->code = $q . $this->getFaker()->numberBetween(1, 50);

        app()->call([$this->contentRepository, 'store'], ['contentRepositoryRequest' => $contentRepositoryRequest]);

        $result = app()->call([$this->contentRepository, 'getDuplicateTitle'], ['q' => $q]);

        self::assertEquals(2, $result);
    }
}
