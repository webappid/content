<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Tests\Unit\Repositories;

use Illuminate\Contracts\Container\BindingResolutionException;
use WebAppId\Content\Models\ContentStatus;
use WebAppId\Content\Repositories\ContentStatusRepository;
use WebAppId\Content\Repositories\Requests\ContentStatusRepositoryRequest;
use WebAppId\Tests\TestCase;
use WebAppId\User\Tests\Unit\Repositories\UserRepositoryTest;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 04:47:50
 * Time: 2020/04/22
 * Class ContentStatusServiceResponseList
 * @package Tests\Unit\Repositories
 */
class ContentStatusRepositoryTest extends TestCase
{

    /**
     * @var ContentStatusRepository
     */
    private $contentStatusRepository;

    /**
     * @var UserRepositoryTest
     */
    private $userRepositoryTest;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        try {
            $this->contentStatusRepository = $this->container->make(ContentStatusRepository::class);
            $this->userRepositoryTest = $this->container->make(UserRepositoryTest::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }
    }

    public function getDummy(int $no = 0): ?ContentStatusRepositoryRequest
    {
        $dummy = null;
        try {
            $dummy = $this->container->make(ContentStatusRepositoryRequest::class);
            $user = $this->container->call([$this->userRepositoryTest, 'testStore']);
            $dummy->name = $this->getFaker()->text(20);
            $dummy->user_id = $user->id;

        } catch (BindingResolutionException $e) {
            report($e);
        }
        return $dummy;
    }

    public function testStore(int $no = 0): ?ContentStatus
    {
        $contentStatusRepositoryRequest = $this->getDummy($no);
        $result = $this->container->call([$this->contentStatusRepository, 'store'], ['contentStatusRepositoryRequest' => $contentStatusRepositoryRequest]);
        self::assertNotEquals(null, $result);
        return $result;
    }

    public function testGetById()
    {
        $contentStatus = $this->testStore();
        $result = $this->container->call([$this->contentStatusRepository, 'getById'], ['id' => $contentStatus->id]);
        self::assertNotEquals(null, $result);
    }

    public function testDelete()
    {
        $contentStatus = $this->testStore();
        $result = $this->container->call([$this->contentStatusRepository, 'delete'], ['id' => $contentStatus->id]);
        self::assertTrue($result);
    }

    public function testGet()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(50, $this->getFaker()->numberBetween(50, 100)); $i++) {
            $this->testStore($i);
        }

        $resultList = $this->container->call([$this->contentStatusRepository, 'get']);
        self::assertGreaterThanOrEqual(1, count($resultList));
    }

    public function testGetCount()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(50, $this->getFaker()->numberBetween(50, 100)); $i++) {
            $this->testStore($i);
        }

        $result = $this->container->call([$this->contentStatusRepository, 'getCount']);
        self::assertGreaterThanOrEqual(1, $result);
    }

    public function testUpdate()
    {
        $contentStatus = $this->testStore();
        $contentStatusRepositoryRequest = $this->getDummy(1);
        $result = $this->container->call([$this->contentStatusRepository, 'update'], ['id' => $contentStatus->id, 'contentStatusRepositoryRequest' => $contentStatusRepositoryRequest]);
        self::assertNotEquals(null, $result);
    }

    public function testGetWhere()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(10, 20)); $i++) {
            $this->testStore($i);
        }
        $string = 'aiueo';
        $q = $string[$this->getFaker()->numberBetween(0, strlen($string) - 1)];
        $result = $this->container->call([$this->contentStatusRepository, 'get'], ['q' => $q]);
        self::assertGreaterThanOrEqual(1, count($result));
    }

    public function testGetWhereCount()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(10, 30)); $i++) {
            $this->testStore($i);
        }
        $string = 'aiueo';
        $q = $string[$this->getFaker()->numberBetween(0, strlen($string) - 1)];
        $result = $this->container->call([$this->contentStatusRepository, 'getCount'], ['q' => $q]);
        self::assertGreaterThanOrEqual(1, $result);
    }
}
