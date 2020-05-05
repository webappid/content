<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Tests\Unit\Repositories;

use Illuminate\Contracts\Container\BindingResolutionException;
use WebAppId\Content\Models\Content;
use WebAppId\Content\Repositories\ContentRepository;
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

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        try {
            $this->contentRepository = $this->container->make(ContentRepository::class);
            $this->userRepositoryTest = $this->container->make(UserRepositoryTest::class);
            $this->contentStatusRepositoryTest = $this->container->make(ContentStatusRepositoryTest::class);
            $this->languageRepositoryTest = $this->container->make(LanguageRepositoryTest::class);
            $this->timezoneRepositoryTest = $this->container->make(TimeZoneRepositoryTest::class);
            $this->fileRepositoryTest = $this->container->make(FileRepositoryTest::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }
    }

    public function getDummy(int $no = 0): ?ContentRepositoryRequest
    {
        $dummy = null;
        try {
            $dummy = $this->container->make(ContentRepositoryRequest::class);
            $file = $this->container->call([$this->fileRepositoryTest, 'testStore']);
            $contentStatus = $this->container->call([$this->contentStatusRepositoryTest, 'testStore']);
            $language = $this->container->call([$this->languageRepositoryTest, 'testStore']);
            $timeZone = $this->container->call([$this->timezoneRepositoryTest, 'testStore']);
            $user = $this->container->call([$this->userRepositoryTest, 'testStore']);

            $dummy->title = $this->getFaker()->text(100);
            $dummy->code = $this->getFaker()->text(20);
            $dummy->description = $this->getFaker()->text(255);
            $dummy->keyword = $this->getFaker()->text(100);
            $dummy->og_title = $this->getFaker()->text(100);
            $dummy->og_description = $this->getFaker()->text(255);
            $dummy->default_image = $file->id;
            $dummy->status_id = $contentStatus->id;
            $dummy->language_id = $language->id;
            $dummy->time_zone_id = $timeZone->id;
            $dummy->publish_date = $this->getFaker()->dateTime();
            $dummy->additional_info = $this->getFaker()->text(255);
            $dummy->content = $this->getFaker()->text(255);
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
        $result = $this->container->call([$this->contentRepository, 'store'], ['contentRepositoryRequest' => $contentRepositoryRequest]);
        self::assertNotEquals(null, $result);
        return $result;
    }

    public function testGetById()
    {
        $content = $this->testStore();
        $result = $this->container->call([$this->contentRepository, 'getById'], ['id' => $content->id]);
        self::assertNotEquals(null, $result);
    }

    public function testGetByCode()
    {
        $content = $this->testStore();
        $result = $this->container->call([$this->contentRepository, 'getByCode'], ['code' => $content->code]);
        self::assertNotEquals(null, $result);
    }

    public function testDelete()
    {
        $content = $this->testStore();
        $result = $this->container->call([$this->contentRepository, 'delete'], ['code' => $content->code]);
        self::assertTrue($result);
    }

    public function testGet()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(10, 30)); $i++) {
            $this->testStore($i);
        }

        $resultList = $this->container->call([$this->contentRepository, 'get']);
        self::assertGreaterThanOrEqual(1, count($resultList));
    }

    public function testGetCount()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(10, 30)); $i++) {
            $this->testStore($i);
        }

        $result = $this->container->call([$this->contentRepository, 'getCount']);

        self::assertGreaterThanOrEqual(1, $result);
    }

    public function testUpdate()
    {
        $content = $this->testStore();
        $contentRepositoryRequest = $this->getDummy(1);
        $result = $this->container->call([$this->contentRepository, 'update'], ['code' => $content->code, 'contentRepositoryRequest' => $contentRepositoryRequest]);
        self::assertNotEquals(null, $result);
    }

    public function testGetWhere()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(10, 30)); $i++) {
            $this->testStore($i);
        }
        $string = 'aiueo';
        $q = $string[$this->getFaker()->numberBetween(0, strlen($string) - 1)];
        $result = $this->container->call([$this->contentRepository, 'get'], ['q' => $q]);
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
        $result = $this->container->call([$this->contentRepository, 'getCount'], ['q' => $q]);
        self::assertGreaterThanOrEqual(1, $result);
    }
}
