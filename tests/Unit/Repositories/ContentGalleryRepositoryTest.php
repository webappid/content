<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Tests\Unit\Repositories;

use Illuminate\Contracts\Container\BindingResolutionException;
use WebAppId\Content\Models\ContentGallery;
use WebAppId\Content\Repositories\ContentGalleryRepository;
use WebAppId\Content\Repositories\Requests\ContentGalleryRepositoryRequest;
use WebAppId\Content\Tests\TestCase;
use WebAppId\User\Tests\Unit\Repositories\UserRepositoryTest;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 15:14:13
 * Time: 2020/04/23
 * Class ContentGalleryServiceResponseList
 * @package Tests\Unit\Repositories
 */
class ContentGalleryRepositoryTest extends TestCase
{

    /**
     * @var ContentGalleryRepository
     */
    private $contentGalleryRepository;

    /**
     * @var FileRepositoryTest
     */
    private $fileRepositoryTest;

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
            $this->contentGalleryRepository = app()->make(ContentGalleryRepository::class);
            $this->contentRepositoryTest = app()->make(ContentRepositoryTest::class);
            $this->userRepositoryTest = app()->make(UserRepositoryTest::class);
            $this->fileRepositoryTest = app()->make(FileRepositoryTest::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }
    }

    public function getDummy(int $no = 0): ?ContentGalleryRepositoryRequest
    {
        $dummy = null;
        try {
            $dummy = app()->make(ContentGalleryRepositoryRequest::class);
            $content = app()->call([$this->contentRepositoryTest, 'testStore']);
            $file = app()->call([$this->fileRepositoryTest, 'testStore']);
            $user = app()->call([$this->userRepositoryTest, 'testStore']);

            $dummy->content_id = $content->id;
            $dummy->file_id = $file->id;
            $dummy->user_id = $user->id;
            $dummy->description = $this->getFaker()->text(100);

        } catch (BindingResolutionException $e) {
            report($e);
        }
        return $dummy;
    }

    public function testStore(int $no = 0): ?ContentGallery
    {
        $contentGalleryRepositoryRequest = $this->getDummy($no);
        $result = app()->call([$this->contentGalleryRepository, 'store'], ['contentGalleryRepositoryRequest' => $contentGalleryRepositoryRequest]);
        self::assertNotEquals(null, $result);
        return $result;
    }

    public function testGetByContentId()
    {
        $contentGallery = $this->testStore();
        $result = app()->call([$this->contentGalleryRepository, 'getByContentId'], ['contentId' => $contentGallery->content_id]);
        self::assertGreaterThanOrEqual(1, count($result));
    }

    public function testDeleteByContentId()
    {
        $contentGallery = $this->testStore();
        $result = app()->call([$this->contentGalleryRepository, 'deleteByContentId'], ['contentId' => $contentGallery->content_id]);
        self::assertTrue($result);
    }

    public function testGet()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(10, 30)); $i++) {
            $this->testStore($i);
        }

        $resultList = app()->call([$this->contentGalleryRepository, 'get']);
        self::assertGreaterThanOrEqual(1, count($resultList));
    }

    public function testGetCount()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(10, $this->getFaker()->numberBetween(10, 30)); $i++) {
            $this->testStore($i);
        }

        $result = app()->call([$this->contentGalleryRepository, 'getCount']);
        self::assertGreaterThanOrEqual(1, $result);
    }

}
