<?php


namespace WebAppId\Content\Tests\Feature\Services;

use Illuminate\Support\Facades\Cache;
use WebAppId\Content\Services\CategoryService;
use WebAppId\Content\Tests\TestCase;
use WebAppId\Content\Tests\Unit\Repositories\CategoryTest;

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
    private $categoryService;

    /**
     * @var CategoryTest
     */
    private $categoryRepositoryTest;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->categoryService = $this->getContainer()->make(CategoryService::class);
        $this->categoryRepositoryTest = $this->getContainer()->make(CategoryTest::class);
        parent::__construct($name, $data, $dataName);
    }

    public function testGetCategoryByCode()
    {
        $dummy = $this->getContainer()->call([$this->categoryRepositoryTest, 'getDummy']);
        $category = $this->categoryRepositoryTest->createCategory($dummy);
        $result = $this->getContainer()->call([$this->categoryService, 'getByCode'], ['code' => $category['code']]);
        self::assertTrue($result->isStatus());
    }

    public function testGetCategoryByParentCode()
    {
        $dummy = $this->getContainer()->call([$this->categoryRepositoryTest, 'getDummy']);
        $category = $this->categoryRepositoryTest->createCategory($dummy);

        $dummy = $this->getContainer()->call([$this->categoryRepositoryTest, 'getDummy']);
        $dummy->setParentId($category->id);
        $this->categoryRepositoryTest->createCategory($dummy);
        $result = $this->getContainer()->call([$this->categoryService, 'getChildByParentCode'], ['code' => $category['code']]);
        Cache::forget($category['code']);
        self::assertTrue($result->isStatus());
    }

    public function testGetCategoryGrandByParentCode()
    {
        $dummy = $this->getContainer()->call([$this->categoryRepositoryTest, 'getDummy']);
        $category = $this->categoryRepositoryTest->createCategory($dummy);

        $dummyChild = $this->getContainer()->call([$this->categoryRepositoryTest, 'getDummy']);
        $dummyChild->setParentId($category->id);
        $categoryChild = $this->categoryRepositoryTest->createCategory($dummyChild);

        $dummyGrandChild = $this->getContainer()->call([$this->categoryRepositoryTest, 'getDummy']);
        $dummyGrandChild->setParentId($categoryChild->id);
        $this->categoryRepositoryTest->createCategory($dummyGrandChild);

        $result = $this->getContainer()->call([$this->categoryService, 'getChildAndGrandByParentCode'], ['code' => $category['code']]);
        Cache::forget($category['code']);
        self::assertTrue($result->isStatus());
    }

    public function testGetSearchCategory()
    {
        $dummy = $this->getContainer()->call([$this->categoryRepositoryTest, 'getDummy']);
        $this->categoryRepositoryTest->createCategory($dummy);
        $result = $this->getContainer()->call([$this->categoryService, 'getSearchCategory'], ['q' => '']);
        self::assertTrue($result->isStatus());
    }
}