<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Tests\Unit\Repositories;

use Illuminate\Contracts\Container\BindingResolutionException;
use WebAppId\Content\Models\Language;
use WebAppId\Content\Repositories\LanguageRepository;
use WebAppId\Content\Repositories\Requests\LanguageRepositoryRequest;
use WebAppId\Tests\TestCase;
use WebAppId\User\Tests\Unit\Repositories\UserRepositoryTest;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 17:23:06
 * Time: 2020/04/22
 * Class LanguageServiceResponseList
 * @package WebAppId\Tests\Unit\Repositories
 */
class LanguageRepositoryTest extends TestCase
{

    /**
     * @var LanguageRepository
     */
    private $languageRepository;

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
            $this->languageRepository = $this->container->make(LanguageRepository::class);
            $this->userRepositoryTest = $this->container->make(UserRepositoryTest::class);
            $this->fileRepositoryTest = $this->container->make(FileRepositoryTest::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }
    }

    public function getDummy(int $no = 0): ?LanguageRepositoryRequest
    {
        $dummy = null;
        try {
            $dummy = $this->container->make(LanguageRepositoryRequest::class);
            $user = $this->container->call([$this->userRepositoryTest, 'testStore']);
            $file = $this->container->call([$this->fileRepositoryTest, 'testStore']);
            $dummy->code = $this->getFaker()->text(5);
            $dummy->name = $this->getFaker()->text(20);
            $dummy->image_id = $file->id;
            $dummy->user_id = $user->id;

        } catch (BindingResolutionException $e) {
            report($e);
        }
        return $dummy;
    }

    public function testStore(int $no = 0): ?Language
    {
        $languageRepositoryRequest = $this->getDummy($no);
        $result = $this->container->call([$this->languageRepository, 'store'], ['languageRepositoryRequest' => $languageRepositoryRequest]);
        self::assertNotEquals(null, $result);
        return $result;
    }

    public function testGetById()
    {
        $language = $this->testStore();
        $result = $this->container->call([$this->languageRepository, 'getById'], ['id' => $language->id]);
        self::assertNotEquals(null, $result);
    }

    public function testDelete()
    {
        $language = $this->testStore();
        $result = $this->container->call([$this->languageRepository, 'delete'], ['id' => $language->id]);
        self::assertTrue($result);
    }

    public function testGet()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(10, 30)); $i++) {
            $this->testStore($i);
        }

        $resultList = $this->container->call([$this->languageRepository, 'get']);
        self::assertGreaterThanOrEqual(1, count($resultList));
    }

    public function testGetCount()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(10, 30)); $i++) {
            $this->testStore($i);
        }

        $result = $this->container->call([$this->languageRepository, 'getCount']);
        self::assertGreaterThanOrEqual(1, $result);
    }

    public function testUpdate()
    {
        $language = $this->testStore();
        $languageRepositoryRequest = $this->getDummy(1);
        $result = $this->container->call([$this->languageRepository, 'update'], ['id' => $language->id, 'languageRepositoryRequest' => $languageRepositoryRequest]);
        self::assertNotEquals(null, $result);
    }

    public function testGetWhere()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(10, 30)); $i++) {
            $this->testStore($i);
        }
        $string = 'aiueo';
        $q = $string[$this->getFaker()->numberBetween(0, strlen($string) - 1)];
        $result = $this->container->call([$this->languageRepository, 'get'], ['q' => $q]);
        self::assertGreaterThanOrEqual(1, count($result));
    }

    public function testGetWhereCount()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(10, 30)); $i++) {
            $this->testStore($i);
        }
        $string = 'aiueo';
        $q = $string[$this->getFaker()->numberBetween(0, strlen($string) - 1)];
        $result = $this->container->call([$this->languageRepository, 'getCount'], ['q' => $q]);
        self::assertGreaterThanOrEqual(1, $result);
    }
}
