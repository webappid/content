<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Tests\Unit\Repositories;

use Illuminate\Contracts\Container\BindingResolutionException;
use WebAppId\Content\Models\Category;
use WebAppId\Content\Repositories\CategoryRepository;
use WebAppId\Content\Repositories\Requests\CategoryRepositoryRequest;
use WebAppId\Content\Tests\TestCase;
use WebAppId\User\Tests\Unit\Repositories\UserRepositoryTest;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 22:58:42
 * Time: 2020/04/21
 * Class CategoryServiceResponseList
 * @package WebAppId\Content\Tests\Unit\Repositories
 */
class CategoryRepositoryTest extends TestCase
{

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var UserRepositoryTest
     */
    private $userRepositoryTest;

    /**
     * @var CategoryStatusRepositoryTest
     */
    private $categoryStatusRepositoryTest;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        try {
            $this->categoryRepository = app()->make(CategoryRepository::class);
            $this->userRepositoryTest = app()->make(UserRepositoryTest::class);
            $this->categoryStatusRepositoryTest = app()->make(CategoryStatusRepositoryTest::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }
    }

    public function getDummy(int $no = 0): ?CategoryRepositoryRequest
    {
        $dummy = null;
        try {
            $dummy = app()->make(CategoryRepositoryRequest::class);
            $user = app()->call([$this->userRepositoryTest, 'testStore']);
            $categoryStatus = app()->call([$this->categoryStatusRepositoryTest, 'testStore']);
            $dummy->code = $this->getFaker()->text(20);
            $dummy->name = $this->getFaker()->text(50);
            $dummy->user_id = $user->id;
            $dummy->status_id = $categoryStatus->id;
            $dummy->parent_id = $this->getFaker()->randomNumber();

        } catch (BindingResolutionException $e) {
            report($e);
        }
        return $dummy;
    }

    public function testStore(int $no = 0): ?Category
    {
        $categoryRepositoryRequest = $this->getDummy($no);
        $result = app()->call([$this->categoryRepository, 'store'], ['categoryRepositoryRequest' => $categoryRepositoryRequest]);
        self::assertNotEquals(null, $result);
        return $result;
    }

    public function testGetById()
    {
        $category = $this->testStore();
        $result = app()->call([$this->categoryRepository, 'getById'], ['id' => $category->id]);
        self::assertNotEquals(null, $result);
    }

    public function testGetByName()
    {
        $category = $this->testStore();
        $result = app()->call([$this->categoryRepository, 'getByName'], ['name' => $category->name]);
        self::assertNotEquals(null, $result);
    }

    public function testGetWhereInName()
    {
        $categories = [];
        for ($i = 0; $i < $this->getFaker()->numberBetween(5, 10); $i++) {
            $category = $this->testStore();
            if ($this->getFaker()->boolean) {
                $categories[] = $category->name;
            }
        }
        $result = app()->call([$this->categoryRepository, 'getWhereInName'], ['names' => $categories]);

        self::assertSameSize($result, $categories);
        return $result;
    }

    public function testGetByCode()
    {
        $category = $this->testStore();
        $result = app()->call([$this->categoryRepository, 'getByCode'], ['code' => $category->code]);
        self::assertNotEquals(null, $result);
    }

    public function testDelete()
    {
        $category = $this->testStore();
        $result = app()->call([$this->categoryRepository, 'delete'], ['id' => $category->id]);
        self::assertTrue($result);
    }

    public function testGet()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(50, $this->getFaker()->numberBetween(50, 100)); $i++) {
            $this->testStore($i);
        }

        $resultList = app()->call([$this->categoryRepository, 'get']);
        self::assertGreaterThanOrEqual(1, count($resultList));
    }

    public function testGetCount()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(50, $this->getFaker()->numberBetween(50, 100)); $i++) {
            $this->testStore($i);
        }

        $result = app()->call([$this->categoryRepository, 'getCount']);
        self::assertGreaterThanOrEqual(1, $result);
    }

    public function testUpdate()
    {
        $category = $this->testStore();
        $categoryRepositoryRequest = $this->getDummy(1);
        $result = app()->call([$this->categoryRepository, 'update'], ['id' => $category->id, 'categoryRepositoryRequest' => $categoryRepositoryRequest]);
        self::assertNotEquals(null, $result);
    }

    public function testGetWhere()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(50, 100)); $i++) {
            $this->testStore($i);
        }
        $string = 'aiueo';
        $q = $string[$this->getFaker()->numberBetween(0, strlen($string) - 1)];
        $result = app()->call([$this->categoryRepository, 'get'], ['q' => $q]);
        self::assertGreaterThanOrEqual(1, count($result));
    }

    public function testGetWhereCount()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(50, $this->getFaker()->numberBetween(50, 100)); $i++) {
            $this->testStore($i);
        }
        $string = 'aiueo';
        $q = $string[$this->getFaker()->numberBetween(0, strlen($string) - 1)];
        $result = app()->call([$this->categoryRepository, 'getCount'], ['q' => $q]);
        self::assertGreaterThanOrEqual(1, $result);
    }
}
