<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Tests\Feature\Services;

use Illuminate\Contracts\Container\BindingResolutionException;
use WebAppId\Content\Services\Requests\TagServiceRequest;
use WebAppId\Content\Services\TagService;
use WebAppId\Content\Tests\TestCase;
use WebAppId\Content\Tests\Unit\Repositories\TagRepositoryTest;
use WebAppId\DDD\Tools\Lazy;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 17:09:16
 * Time: 2020/07/28
 * Class TagServiceResponseList
 * @package WebAppId\Content\Tests\Feature\Services
 */
class TagServiceTest extends TestCase
{

    /**
     * @var TagService
     */
    protected $tagService;

    /**
     * @var TagRepositoryTest
     */
    protected $tagRepositoryTest;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        try {
            $this->tagService = $this->container->make(TagService::class);
            $this->tagRepositoryTest = $this->container->make(TagRepositoryTest::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }

    }

    public function testGetById()
    {
        $contentServiceResponse = $this->testStore();
        $result = $this->container->call([$this->tagService, 'getById'], ['id' => $contentServiceResponse->tag->id]);
        self::assertTrue($result->status);
    }

    private function getDummy(int $number = 0): TagServiceRequest
    {
        $tagRepositoryRequest = $this->container->call([$this->tagRepositoryTest, 'getDummy'], ['no' => $number]);
        $tagServiceRequest = null;
        try {
            $tagServiceRequest = $this->container->make(TagServiceRequest::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }
        return Lazy::copy($tagRepositoryRequest, $tagServiceRequest, Lazy::AUTOCAST);
    }

    public function testStore(int $number = 0)
    {
        $tagServiceRequest = $this->getDummy($number);
        $result = $this->container->call([$this->tagService, 'store'], ['tagServiceRequest' => $tagServiceRequest]);
        self::assertTrue($result->status);
        return $result;
    }

    public function testGet()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(10, 30)); $i++) {
            $this->testStore($i);
        }
        $result = $this->container->call([$this->tagService, 'get']);
        self::assertTrue($result->status);
    }

    public function testGetCount()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(10, 30)); $i++) {
            $this->testStore($i);
        }
        $result = $this->container->call([$this->tagService, 'getCount']);
        self::assertGreaterThanOrEqual(1, $result);
    }

    public function testUpdate()
    {
        $contentServiceResponse = $this->testStore();
        $tagServiceRequest = $this->getDummy();
        $result = $this->container->call([$this->tagService, 'update'], ['id' => $contentServiceResponse->tag->id, 'tagServiceRequest' => $tagServiceRequest]);
        self::assertNotEquals(null, $result);
    }

    public function testDelete()
    {
        $contentServiceResponse = $this->testStore();
        $result = $this->container->call([$this->tagService, 'delete'], ['id' => $contentServiceResponse->tag->id]);
        self::assertTrue($result);
    }

    public function testGetWhere()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(10, 30)); $i++) {
            $this->testStore($i);
        }
        $string = 'aiueo';
        $q = $string[$this->getFaker()->numberBetween(0, strlen($string) - 1)];
        $result = $this->container->call([$this->tagService, 'get'], ['q' => $q]);
        self::assertTrue($result->status);
    }

    public function testGetWhereCount()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(10, 30)); $i++) {
            $this->testStore($i);
        }
        $string = 'aiueo';
        $q = $string[$this->getFaker()->numberBetween(0, strlen($string) - 1)];
        $result = $this->container->call([$this->tagService, 'getCount'], ['q' => $q]);
        self::assertGreaterThanOrEqual(1, $result);
    }
}
