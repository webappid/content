<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Tests\Unit\Repositories;

use Illuminate\Contracts\Container\BindingResolutionException;
use WebAppId\Content\Models\MimeType;
use WebAppId\Content\Repositories\MimeTypeRepository;
use WebAppId\Content\Repositories\Requests\MimeTypeRepositoryRequest;
use WebAppId\Content\Tests\TestCase;
use WebAppId\User\Tests\Unit\Repositories\UserRepositoryTest;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 04:25:12
 * Time: 2020/04/22
 * Class MimeTypeServiceResponseList
 * @package WebAppId\Content\Tests\Unit\Repositories
 */
class MimeTypeRepositoryTest extends TestCase
{

    /**
     * @var MimeTypeRepository
     */
    private $mimeTypeRepository;

    /**
     * @var UserRepositoryTest
     */
    private $userRepositoryTest;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        try {
            $this->mimeTypeRepository = app()->make(MimeTypeRepository::class);
            $this->userRepositoryTest = app()->make(UserRepositoryTest::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }
    }

    public function getDummy(int $no = 0): ?MimeTypeRepositoryRequest
    {
        $dummy = null;
        try {
            $dummy = app()->make(MimeTypeRepositoryRequest::class);
            $user = app()->call([$this->userRepositoryTest, 'testStore']);
            $dummy->name = $this->getFaker()->text(255);
            $dummy->user_id = $user->id;

        } catch (BindingResolutionException $e) {
            report($e);
        }
        return $dummy;
    }

    public function testStore(int $no = 0): ?MimeType
    {
        $mimeTypeRepositoryRequest = $this->getDummy($no);
        $result = app()->call([$this->mimeTypeRepository, 'store'], ['mimeTypeRepositoryRequest' => $mimeTypeRepositoryRequest]);
        self::assertNotEquals(null, $result);
        return $result;
    }

    public function testGetById()
    {
        $mimeType = $this->testStore();
        $result = app()->call([$this->mimeTypeRepository, 'getById'], ['id' => $mimeType->id]);
        self::assertNotEquals(null, $result);
    }

    public function testGetByName()
    {
        $mimeType = $this->testStore();
        $result = app()->call([$this->mimeTypeRepository, 'getByName'], ['name' => $mimeType->name]);
        self::assertNotEquals(null, $result);
    }

    public function testDelete()
    {
        $mimeType = $this->testStore();
        $result = app()->call([$this->mimeTypeRepository, 'delete'], ['id' => $mimeType->id]);
        self::assertTrue($result);
    }

    public function testGet()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(10, 30)); $i++) {
            $this->testStore($i);
        }

        $resultList = app()->call([$this->mimeTypeRepository, 'get']);
        self::assertGreaterThanOrEqual(1, count($resultList));
    }

    public function testGetCount()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(10, 30)); $i++) {
            $this->testStore($i);
        }

        $result = app()->call([$this->mimeTypeRepository, 'getCount']);
        self::assertGreaterThanOrEqual(1, $result);
    }

    public function testUpdate()
    {
        $mimeType = $this->testStore();
        $mimeTypeRepositoryRequest = $this->getDummy(1);
        $result = app()->call([$this->mimeTypeRepository, 'update'], ['id' => $mimeType->id, 'mimeTypeRepositoryRequest' => $mimeTypeRepositoryRequest]);
        self::assertNotEquals(null, $result);
    }

    public function testGetWhere()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(10, 30)); $i++) {
            $this->testStore($i);
        }
        $string = 'aiueo';
        $q = $string[$this->getFaker()->numberBetween(0, strlen($string) - 1)];
        $result = app()->call([$this->mimeTypeRepository, 'get'], ['q' => $q]);
        self::assertGreaterThanOrEqual(1, count($result));
    }

    public function testGetWhereCount()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(10, 30)); $i++) {
            $this->testStore($i);
        }
        $string = 'aiueo';
        $q = $string[$this->getFaker()->numberBetween(0, strlen($string) - 1)];
        $result = app()->call([$this->mimeTypeRepository, 'getCount'], ['q' => $q]);
        self::assertGreaterThanOrEqual(1, $result);
    }
}
