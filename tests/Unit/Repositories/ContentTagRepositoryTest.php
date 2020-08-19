<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Tests\Unit\Repositories;

use Illuminate\Contracts\Container\BindingResolutionException;
use WebAppId\Content\Models\ContentTag;
use WebAppId\Content\Repositories\ContentTagRepository;
use WebAppId\Content\Repositories\Requests\ContentTagRepositoryRequest;
use WebAppId\Content\Tests\TestCase;
use WebAppId\User\Tests\Unit\Repositories\UserRepositoryTest;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 01:03:14
 * Time: 2020/04/23
 * Class ContentTagServiceResponseList
 * @package Tests\Unit\Repositories
 */
class ContentTagRepositoryTest extends TestCase
{

    /**
     * @var ContentTagRepository
     */
    private $contentTagRepository;

    /**
     * @var TagRepositoryTest
     */
    private $tagRepositoryTest;

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
            $this->contentTagRepository = app()->make(ContentTagRepository::class);
            $this->tagRepositoryTest = app()->make(TagRepositoryTest::class);
            $this->contentRepositoryTest = app()->make(ContentRepositoryTest::class);
            $this->userRepositoryTest = app()->make(UserRepositoryTest::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }
    }

    public function getDummy(int $no = 0): ?ContentTagRepositoryRequest
    {
        $dummy = null;
        try {
            $dummy = app()->make(ContentTagRepositoryRequest::class);
            $content = app()->call([$this->contentRepositoryTest, 'testStore']);
            $tag = app()->call([$this->tagRepositoryTest, 'testStore']);
            $user = app()->call([$this->userRepositoryTest, 'testStore']);

            $dummy->content_id = $content->id;
            $dummy->tag_id = $tag->id;
            $dummy->user_id = $user->id;

        } catch (BindingResolutionException $e) {
            report($e);
        }
        return $dummy;
    }

    public function testStore(int $no = 0): ?ContentTag
    {
        $contentTagRepositoryRequest = $this->getDummy($no);
        $result = app()->call([$this->contentTagRepository, 'store'], ['contentTagRepositoryRequest' => $contentTagRepositoryRequest]);
        self::assertNotEquals(null, $result);
        return $result;
    }

    public function testGetById()
    {
        $contentTag = $this->testStore();
        $result = app()->call([$this->contentTagRepository, 'getByContentId'], ['content_id' => $contentTag->content_id]);
        self::assertNotEquals(null, $result);
    }

    public function testDelete()
    {
        $contentTag = $this->testStore();
        $result = app()->call([$this->contentTagRepository, 'deleteByContentId'], ['content_id' => $contentTag->content_id]);
        self::assertTrue($result);
    }
}
