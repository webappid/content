<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Tests\Unit\Repositories;

use Illuminate\Contracts\Container\BindingResolutionException;
use WebAppId\Content\Models\Tag;
use WebAppId\Content\Repositories\Requests\TagRepositoryRequest;
use WebAppId\Content\Repositories\TagRepository;
use WebAppId\Content\Tests\TestCase;
use WebAppId\User\Tests\Unit\Repositories\UserRepositoryTest;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 05:01:58
 * Time: 2020/04/22
 * Class TagServiceResponseList
 * @package WebAppId\Content\Tests\Unit\Repositories
 */
class TagRepositoryTest extends TestCase
{

    /**
     * @var TagRepository
     */
    private $tagRepository;

    /**
     * @var UserRepositoryTest
     */
    private $userRepositoryTest;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        try {
            $this->tagRepository = app()->make(TagRepository::class);
            $this->userRepositoryTest = app()->make(UserRepositoryTest::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }
    }

    public function getDummy(int $no = 0): ?TagRepositoryRequest
    {
        $dummy = null;
        try {
            $dummy = app()->make(TagRepositoryRequest::class);
            $user = app()->call([$this->userRepositoryTest, 'testStore']);
            $dummy->name = $this->getFaker()->text(255);
            $dummy->user_id = $user->id;

        } catch (BindingResolutionException $e) {
            report($e);
        }
        return $dummy;
    }

    public function testStore(int $no = 0): ?Tag
    {
        $tagRepositoryRequest = $this->getDummy($no);
        $result = app()->call([$this->tagRepository, 'store'], ['tagRepositoryRequest' => $tagRepositoryRequest]);
        self::assertNotEquals(null, $result);
        return $result;
    }

    public function testGetById()
    {
        $tag = $this->testStore();
        $result = app()->call([$this->tagRepository, 'getById'], ['id' => $tag->id]);
        self::assertNotEquals(null, $result);
    }

    public function testGetByName()
    {
        $tag = $this->testStore();
        $result = app()->call([$this->tagRepository, 'getByName'], ['name' => $tag->name]);
        self::assertNotEquals(null, $result);
    }

    public function testDelete()
    {
        $tag = $this->testStore();
        $result = app()->call([$this->tagRepository, 'delete'], ['id' => $tag->id]);
        self::assertTrue($result);
    }

    public function testGet()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(50, $this->getFaker()->numberBetween(50, 100)); $i++) {
            $this->testStore($i);
        }

        $resultList = app()->call([$this->tagRepository, 'get']);
        self::assertGreaterThanOrEqual(1, count($resultList));
    }

    public function testGetCount()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(50, $this->getFaker()->numberBetween(50, 100)); $i++) {
            $this->testStore($i);
        }

        $result = app()->call([$this->tagRepository, 'getCount']);
        self::assertGreaterThanOrEqual(1, $result);
    }

    public function testUpdate()
    {
        $tag = $this->testStore();
        $tagRepositoryRequest = $this->getDummy(1);
        $result = app()->call([$this->tagRepository, 'update'], ['id' => $tag->id, 'tagRepositoryRequest' => $tagRepositoryRequest]);
        self::assertNotEquals(null, $result);
    }

    public function testGetWhere()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(50, $this->getFaker()->numberBetween(50, 100)); $i++) {
            $this->testStore($i);
        }
        $string = 'aiueo';
        $q = $string[$this->getFaker()->numberBetween(0, strlen($string) - 1)];
        $result = app()->call([$this->tagRepository, 'get'], ['q' => $q]);
        self::assertGreaterThanOrEqual(1, count($result));
    }

    public function testGetWhereCount()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(50, $this->getFaker()->numberBetween(50, 100)); $i++) {
            $this->testStore($i);
        }
        $string = 'aiueo';
        $q = $string[$this->getFaker()->numberBetween(0, strlen($string) - 1)];
        $result = app()->call([$this->tagRepository, 'getCount'], ['q' => $q]);
        self::assertGreaterThanOrEqual(1, $result);
    }
}
