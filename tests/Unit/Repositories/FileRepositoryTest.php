<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Tests\Unit\Repositories;

use Illuminate\Contracts\Container\BindingResolutionException;
use WebAppId\Content\Models\File;
use WebAppId\Content\Repositories\FileRepository;
use WebAppId\Content\Repositories\Requests\FileRepositoryRequest;
use WebAppId\Content\Tests\TestCase;
use WebAppId\User\Tests\Unit\Repositories\UserRepositoryTest;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 04:10:49
 * Time: 2020/04/22
 * Class FileServiceResponseList
 * @package Tests\Unit\Repositories
 */
class FileRepositoryTest extends TestCase
{

    /**
     * @var FileRepository
     */
    private $fileRepository;

    /**
     * @var UserRepositoryTest
     */
    private $userRepositoryTest;

    /**
     * @var MimeTypeRepositoryTest
     */
    private $mimeTypeRepositoryTest;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        try {
            $this->fileRepository = app()->make(FileRepository::class);
            $this->userRepositoryTest = app()->make(UserRepositoryTest::class);
            $this->mimeTypeRepositoryTest = app()->make(MimeTypeRepositoryTest::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }
    }

    public function getDummy(int $no = 0): ?FileRepositoryRequest
    {
        $dummy = null;
        try {
            $dummy = app()->make(FileRepositoryRequest::class);
            $user = app()->call([$this->userRepositoryTest, 'testStore']);
            $mimeType = app()->call([$this->mimeTypeRepositoryTest, 'testStore']);
            $dummy->name = $this->getFaker()->text(255);
            $dummy->description = $this->getFaker()->text(255);
            $dummy->alt = $this->getFaker()->text(100);
            $dummy->path = $this->getFaker()->text(255);
            $dummy->mime_type_id = $mimeType->id;
            $dummy->creator_id = $user->id;
            $dummy->owner_id = $user->id;
            $dummy->user_id = $user->id;

        } catch (BindingResolutionException $e) {
            report($e);
        }
        return $dummy;
    }

    public function testStore(int $no = 0): ?File
    {
        $fileRepositoryRequest = $this->getDummy($no);
        $result = app()->call([$this->fileRepository, 'store'], ['fileRepositoryRequest' => $fileRepositoryRequest]);
        self::assertNotEquals(null, $result);
        return $result;
    }

    public function testGetById()
    {
        $file = $this->testStore();
        $result = app()->call([$this->fileRepository, 'getById'], ['id' => $file->id]);
        self::assertNotEquals(null, $result);
    }

    public function testGetByName()
    {
        $file = $this->testStore();
        $result = app()->call([$this->fileRepository, 'getByName'], ['name' => $file->name]);
        self::assertNotEquals(null, $result);
    }

    public function testDelete()
    {
        $file = $this->testStore();
        $result = app()->call([$this->fileRepository, 'delete'], ['id' => $file->id]);
        self::assertTrue($result);
    }

    public function testGet()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(10, 30)); $i++) {
            $this->testStore($i);
        }

        $resultList = app()->call([$this->fileRepository, 'get']);
        self::assertGreaterThanOrEqual(1, count($resultList));
    }

    public function testGetCount()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(10, 30)); $i++) {
            $this->testStore($i);
        }

        $result = app()->call([$this->fileRepository, 'getCount']);
        self::assertGreaterThanOrEqual(1, $result);
    }

    public function testUpdate()
    {
        $file = $this->testStore();
        $fileRepositoryRequest = $this->getDummy(1);
        $result = app()->call([$this->fileRepository, 'update'], ['id' => $file->id, 'fileRepositoryRequest' => $fileRepositoryRequest]);
        self::assertNotEquals(null, $result);
    }

    public function testGetWhere()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(10, 30)); $i++) {
            $this->testStore($i);
        }
        $string = 'aiueo';
        $q = $string[$this->getFaker()->numberBetween(0, strlen($string) - 1)];
        $result = app()->call([$this->fileRepository, 'get'], ['q' => $q]);
        self::assertGreaterThanOrEqual(1, count($result));
    }

    public function testGetWhereCount()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(10, 30)); $i++) {
            $this->testStore($i);
        }
        $string = 'aiueo';
        $q = $string[$this->getFaker()->numberBetween(0, strlen($string) - 1)];
        $result = app()->call([$this->fileRepository, 'getCount'], ['q' => $q]);
        self::assertGreaterThanOrEqual(1, $result);
    }
}
