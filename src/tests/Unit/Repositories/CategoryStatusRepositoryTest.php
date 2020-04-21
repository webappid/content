<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Tests\Unit\Repositories;

use Illuminate\Contracts\Container\BindingResolutionException;
use WebAppId\Content\Models\CategoryStatus;
use WebAppId\Content\Repositories\CategoryStatusRepository;
use WebAppId\Content\Repositories\Requests\CategoryStatusRepositoryRequest;
use WebAppId\Content\Tests\TestCase;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 22/04/20
 * Time: 03.21
 * Class CategoryStatusRepositoryTest
 * @package WebAppId\Content\Tests\Unit\Repositories
 */
class CategoryStatusRepositoryTest extends TestCase
{

    /**
     * @var CategoryStatusRepository
     */
    private $categoryStatusRepository;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        try {
            $this->categoryStatusRepository = $this->container->make(CategoryStatusRepository::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }
    }

    public function getDummy(int $no = 0): ?CategoryStatusRepositoryRequest
    {
        $dummy = null;
        try {
            $dummy = $this->container->make(CategoryStatusRepositoryRequest::class);
            $dummy->name = $this->getFaker()->text(255);

        } catch (BindingResolutionException $e) {
            report($e);
        }
        return $dummy;
    }

    public function testStore(int $no = 0): ?CategoryStatus
    {
        $categoryStatusRepositoryRequest = $this->getDummy($no);
        $result = $this->container->call([$this->categoryStatusRepository, 'store'], ['categoryStatusRepositoryRequest' => $categoryStatusRepositoryRequest]);
        self::assertNotEquals(null, $result);
        return $result;
    }

    public function testGetById()
    {
        $categoryStatus = $this->testStore();
        $result = $this->container->call([$this->categoryStatusRepository, 'getById'], ['id' => $categoryStatus->id]);
        self::assertNotEquals(null, $result);
    }

    public function testDelete()
    {
        $categoryStatus = $this->testStore();
        $result = $this->container->call([$this->categoryStatusRepository, 'delete'], ['id' => $categoryStatus->id]);
        self::assertTrue($result);
    }

    public function testGet()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(20, 100)); $i++) {
            $this->testStore($i);
        }

        $resultList = $this->container->call([$this->categoryStatusRepository, 'get']);
        self::assertGreaterThanOrEqual(1, count($resultList));
    }

    public function testUpdate()
    {
        $categoryStatus = $this->testStore();
        $categoryStatusRepositoryRequest = $this->getDummy(1);
        $result = $this->container->call([$this->categoryStatusRepository, 'update'], ['id' => $categoryStatus->id, 'categoryStatusRepositoryRequest' => $categoryStatusRepositoryRequest]);
        self::assertNotEquals(null, $result);
    }

    public function testGetWhere()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(50, $this->getFaker()->numberBetween(50, 100)); $i++) {
            $this->testStore($i);
        }
        $string = 'aiueo';
        $q = $string[$this->getFaker()->numberBetween(0, strlen($string) - 1)];
        $result = $this->container->call([$this->categoryStatusRepository, 'getWhere'], ['q' => $q]);
        self::assertGreaterThanOrEqual(1, count($result));
    }

    public function testGetWhereCount()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(50, $this->getFaker()->numberBetween(50, 100)); $i++) {
            $this->testStore($i);
        }
        $string = 'aiueo';
        $q = $string[$this->getFaker()->numberBetween(0, strlen($string) - 1)];
        $result = $this->container->call([$this->categoryStatusRepository, 'getWhereCount'], ['q' => $q]);
        self::assertGreaterThanOrEqual(1, $result);
    }
}
