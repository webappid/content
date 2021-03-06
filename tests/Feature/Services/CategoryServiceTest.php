<?php


namespace WebAppId\Content\Tests\Feature\Services;

use Illuminate\Contracts\Container\BindingResolutionException;
use WebAppId\Content\Services\CategoryService;
use WebAppId\Content\Services\Requests\CategoryServiceRequest;
use WebAppId\Content\Tests\TestCase;
use WebAppId\Content\Tests\Unit\Repositories\CategoryRepositoryTest;
use WebAppId\DDD\Tools\Lazy;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 2019-06-07
 * Time: 13:24
 * Class CategoryServiceTest
 * @package WebAppId\Content\Tests\Feature\Services
 */
class CategoryServiceTest extends TestCase
{
    /**
     * @var CategoryService
     */
    protected $categoryService;

    /**
     * @var CategoryRepositoryTest
     */
    protected $categoryRepositoryTest;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        try {
            $this->categoryService = app()->make(CategoryService::class);
            $this->categoryRepositoryTest = app()->make(CategoryRepositoryTest::class);
        } catch (BindingResolutionException $e) {
        }

    }

    public function testGetById()
    {
        $contentServiceResponse = $this->testStore();
        $result = app()->call([$this->categoryService, 'getById'], ['id' => $contentServiceResponse->id]);
        self::assertTrue($result->status);
    }

    private function getDummy(int $number = 0): CategoryServiceRequest
    {
        $categoryRepositoryRequest = app()->call([$this->categoryRepositoryTest, 'getDummy'], ['no' => $number]);
        $categoryServiceRequest = null;
        try {
            $categoryServiceRequest = app()->make(CategoryServiceRequest::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }
        return Lazy::copy($categoryRepositoryRequest, $categoryServiceRequest);
    }

    public function testStore(int $number = 0)
    {
        $result = app()->call([$this->categoryRepositoryTest, 'testStore']);
        self::assertNotEquals(null, $result);
        return $result;
    }

    public function testGet()
    {
        for ($i=0; $i<$this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(10, 30)); $i++){
            $this->testStore($i);
        }
        $result = app()->call([$this->categoryService, 'get']);
        self::assertTrue($result->status);
    }

    public function testGetCount()
    {
        for ($i=0; $i<$this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(10, 30)); $i++){
            $this->testStore($i);
        }
        $result = app()->call([$this->categoryService, 'getCount']);
        self::assertGreaterThanOrEqual(1, $result);
    }

    public function testGetWhere()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(10, 30)); $i++) {
            $this->testStore($i);
        }
        $string = 'aiueo';
        $q = $string[$this->getFaker()->numberBetween(0, strlen($string) - 1)];
        $result = app()->call([$this->categoryService, 'get'], ['q' => $q]);
        self::assertTrue($result->status);
    }

    public function testGetWhereCount()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(10, 30)); $i++) {
            $this->testStore($i);
        }
        $string = 'aiueo';
        $q = $string[$this->getFaker()->numberBetween(0, strlen($string) - 1)];
        $result = app()->call([$this->categoryService, 'getCount'], ['q' => $q]);
        self::assertGreaterThanOrEqual(1, $result);
    }
}
