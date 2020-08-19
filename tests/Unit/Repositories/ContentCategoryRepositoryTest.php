<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Tests\Unit\Repositories;

use Illuminate\Contracts\Container\BindingResolutionException;
use WebAppId\Content\Models\ContentCategory;
use WebAppId\Content\Repositories\ContentCategoryRepository;
use WebAppId\Content\Repositories\Requests\ContentCategoryRepositoryRequest;
use WebAppId\Content\Tests\TestCase;
use WebAppId\User\Tests\Unit\Repositories\UserRepositoryTest;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 10:56:04
 * Time: 2020/04/23
 * Class ContentCategoryServiceResponseList
 * @package WebAppId\Content\Tests\Unit\Repositories
 */
class ContentCategoryRepositoryTest extends TestCase
{

    /**
     * @var ContentCategoryRepository
     */
    private $contentCategoryRepository;

    /**
     * @var ContentRepositoryTest
     */
    private $contentRepositoryTest;

    /**
     * @var CategoryRepositoryTest
     */
    private $categoryRepositoryTest;

    /**
     * @var UserRepositoryTest
     */
    private $userRepositoryTest;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        try {
            $this->contentCategoryRepository = app()->make(ContentCategoryRepository::class);
            $this->contentRepositoryTest = app()->make(ContentRepositoryTest::class);
            $this->categoryRepositoryTest = app()->make(CategoryRepositoryTest::class);
            $this->userRepositoryTest = app()->make(UserRepositoryTest::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }
    }

    public function getDummy(int $no = 0): ?ContentCategoryRepositoryRequest
    {
        $dummy = null;
        try {
            $dummy = app()->make(ContentCategoryRepositoryRequest::class);
            $content = app()->call([$this->contentRepositoryTest, 'testStore']);
            $user = app()->call([$this->userRepositoryTest, 'testStore']);
            $category = app()->call([$this->categoryRepositoryTest, 'testStore']);

            $dummy->content_id = $content->id;
            $dummy->category_id = $category->id;
            $dummy->user_id = $user->id;

        } catch (BindingResolutionException $e) {
            report($e);
        }
        return $dummy;
    }

    public function testStore(int $no = 0): ?ContentCategory
    {
        $contentCategoryRepositoryRequest = $this->getDummy($no);
        $result = app()->call([$this->contentCategoryRepository, 'store'], ['contentCategoryRepositoryRequest' => $contentCategoryRepositoryRequest]);
        self::assertNotEquals(null, $result);
        return $result;
    }

    public function testGetById()
    {
        $contentCategory = $this->testStore();
        $result = app()->call([$this->contentCategoryRepository, 'getById'], ['id' => $contentCategory->id]);
        self::assertNotEquals(null, $result);
    }

    public function testDelete()
    {
        $contentCategory = $this->testStore();
        $result = app()->call([$this->contentCategoryRepository, 'delete'], ['id' => $contentCategory->id]);
        self::assertTrue($result);
    }

    public function testDeleteByContentId()
    {
        $contentCategory = $this->testStore();
        $result = app()->call([$this->contentCategoryRepository, 'deleteByContentId'], ['contentId' => $contentCategory->content_id]);
        self::assertTrue($result);
    }

    public function testGet()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(10, 30)); $i++) {
            $this->testStore($i);
        }

        $resultList = app()->call([$this->contentCategoryRepository, 'get']);
        self::assertGreaterThanOrEqual(1, count($resultList));
    }

    public function testGetCount()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(10, 30)); $i++) {
            $this->testStore($i);
        }

        $result = app()->call([$this->contentCategoryRepository, 'getCount']);
        self::assertGreaterThanOrEqual(1, $result);
    }

    public function testUpdate()
    {
        $contentCategory = $this->testStore();
        $contentCategoryRepositoryRequest = $this->getDummy(1);
        $result = app()->call([$this->contentCategoryRepository, 'update'], ['id' => $contentCategory->id, 'contentCategoryRepositoryRequest' => $contentCategoryRepositoryRequest]);
        self::assertNotEquals(null, $result);
    }
}
