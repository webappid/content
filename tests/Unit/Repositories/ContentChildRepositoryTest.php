<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Tests\Unit\Repositories;

use Illuminate\Contracts\Container\BindingResolutionException;
use WebAppId\Content\Models\ContentChild;
use WebAppId\Content\Repositories\ContentChildRepository;
use WebAppId\Content\Repositories\Requests\ContentChildRepositoryRequest;
use WebAppId\Content\Tests\TestCase;
use WebAppId\User\Tests\Unit\Repositories\UserRepositoryTest;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 15:48:28
 * Time: 2020/04/23
 * Class ContentChildServiceResponseList
 * @package WebAppId\Content\Tests\Unit\Repositories
 */
class ContentChildRepositoryTest extends TestCase
{

    /**
     * @var ContentChildRepository
     */
    private $contentChildRepository;

    /**
     * @var ContentRepositoryTest
     */
    private $contentRepositoryTest;

    /**
     * @var UserRepositoryTest
     */
    private $userRepositoryTest;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        try {
            $this->contentChildRepository = app()->make(ContentChildRepository::class);
            $this->contentRepositoryTest = app()->make(ContentRepositoryTest::class);
            $this->userRepositoryTest = app()->make(UserRepositoryTest::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }
    }

    public function getDummy(int $no = 0): ?ContentChildRepositoryRequest
    {
        $dummy = null;
        try {
            $dummy = app()->make(ContentChildRepositoryRequest::class);
            $parent = app()->call([$this->contentRepositoryTest, 'testStore']);
            $child = app()->call([$this->contentRepositoryTest, 'testStore']);
            $user = app()->call([$this->userRepositoryTest, 'testStore']);

            $dummy->content_parent_id = $parent->id;
            $dummy->content_child_id = $child->id;
            $dummy->user_id = $user->id;

        } catch (BindingResolutionException $e) {
            report($e);
        }
        return $dummy;
    }

    public function testStore(int $no = 0): ?ContentChild
    {
        $contentChildRepositoryRequest = $this->getDummy($no);
        $result = app()->call([$this->contentChildRepository, 'store'], ['contentChildRepositoryRequest' => $contentChildRepositoryRequest]);
        self::assertNotEquals(null, $result);
        return $result;
    }

    public function testGetById()
    {
        $contentChild = $this->testStore();
        $result = app()->call([$this->contentChildRepository, 'getById'], ['id' => $contentChild->id]);
        self::assertNotEquals(null, $result);
    }

    public function testDelete()
    {
        $contentChild = $this->testStore();
        $result = app()->call([$this->contentChildRepository, 'delete'], ['id' => $contentChild->id]);
        self::assertTrue($result);
    }

    public function testDeleteByParentId()
    {
        $contentChild = $this->testStore();
        $result = app()->call([$this->contentChildRepository, 'deleteByParentId'], ['parentId' => $contentChild->content_parent_id]);
        self::assertTrue($result);
    }

}
