<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Tests\Unit\Repositories;

use Illuminate\Contracts\Container\BindingResolutionException;
use WebAppId\Content\Models\TimeZone;
use WebAppId\Content\Repositories\Requests\TimeZoneRepositoryRequest;
use WebAppId\Content\Repositories\TimeZoneRepository;
use WebAppId\Content\Tests\TestCase;
use WebAppId\User\Tests\Unit\Repositories\UserRepositoryTest;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 04:54:28
 * Time: 2020/04/22
 * Class TimeZoneServiceResponseList
 * @package WebAppId\Content\Tests\Unit\Repositories
 */
class TimeZoneRepositoryTest extends TestCase
{

    /**
     * @var TimeZoneRepository
     */
    private $timeZoneRepository;

    /**
     * @var UserRepositoryTest
     */
    private $userRepositoryTest;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        try {
            $this->timeZoneRepository = app()->make(TimeZoneRepository::class);
            $this->userRepositoryTest = app()->make(UserRepositoryTest::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }
    }

    public function getDummy(int $no = 0): ?TimeZoneRepositoryRequest
    {
        $dummy = null;
        try {
            $dummy = app()->make(TimeZoneRepositoryRequest::class);
            $user = app()->call([$this->userRepositoryTest, 'testStore']);
            $dummy->code = $this->getFaker()->text(255);
            $dummy->name = $this->getFaker()->text(255);
            $dummy->minute = $this->getFaker()->randomNumber();
            $dummy->user_id = $user->id;

        } catch (BindingResolutionException $e) {
            report($e);
        }
        return $dummy;
    }

    public function testStore(int $no = 0): ?TimeZone
    {
        $timeZoneRepositoryRequest = $this->getDummy($no);
        $result = app()->call([$this->timeZoneRepository, 'store'], ['timeZoneRepositoryRequest' => $timeZoneRepositoryRequest]);
        self::assertNotEquals(null, $result);
        return $result;
    }

    public function testGetById()
    {
        $timeZone = $this->testStore();
        $result = app()->call([$this->timeZoneRepository, 'getById'], ['id' => $timeZone->id]);
        self::assertNotEquals(null, $result);
    }

    public function testGetByName()
    {
        $timeZone = $this->testStore();
        $result = app()->call([$this->timeZoneRepository, 'getByName'], ['name' => $timeZone->name]);
        self::assertNotEquals(null, $result);
    }

    public function testDelete()
    {
        $timeZone = $this->testStore();
        $result = app()->call([$this->timeZoneRepository, 'delete'], ['id' => $timeZone->id]);
        self::assertTrue($result);
    }

    public function testGet()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(10, 30)); $i++) {
            $this->testStore($i);
        }

        $resultList = app()->call([$this->timeZoneRepository, 'get']);
        self::assertGreaterThanOrEqual(1, count($resultList));
    }

    public function testGetCount()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(10, 30)); $i++) {
            $this->testStore($i);
        }

        $result = app()->call([$this->timeZoneRepository, 'getCount']);
        self::assertGreaterThanOrEqual(1, $result);
    }

    public function testUpdate()
    {
        $timeZone = $this->testStore();
        $timeZoneRepositoryRequest = $this->getDummy(1);
        $result = app()->call([$this->timeZoneRepository, 'update'], ['id' => $timeZone->id, 'timeZoneRepositoryRequest' => $timeZoneRepositoryRequest]);
        self::assertNotEquals(null, $result);
    }

    public function testGetWhere()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(10, 30)); $i++) {
            $this->testStore($i);
        }
        $string = 'aiueo';
        $q = $string[$this->getFaker()->numberBetween(0, strlen($string) - 1)];
        $result = app()->call([$this->timeZoneRepository, 'get'], ['q' => $q]);
        self::assertGreaterThanOrEqual(1, count($result));
    }

    public function testGetWhereCount()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(10, 30)); $i++) {
            $this->testStore($i);
        }
        $string = 'aiueo';
        $q = $string[$this->getFaker()->numberBetween(0, strlen($string) - 1)];
        $result = app()->call([$this->timeZoneRepository, 'getCount'], ['q' => $q]);
        self::assertGreaterThanOrEqual(1, $result);
    }
}
