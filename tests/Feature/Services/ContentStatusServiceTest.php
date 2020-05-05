<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-01-19
 * Time: 00:06
 */

namespace WebAppId\Content\Tests\Feature\Services;


use Illuminate\Contracts\Container\BindingResolutionException;
use WebAppId\Content\Services\ContentStatusService;
use WebAppId\Content\Services\Requests\ContentStatusServiceRequest;
use WebAppId\Content\Tests\TestCase;
use WebAppId\Content\Tests\Unit\Repositories\ContentStatusRepositoryTest;
use WebAppId\DDD\Tools\Lazy;

class ContentStatusServiceTest extends TestCase
{
    /**
     * @var ContentStatusService
     */
    protected $contentStatusService;

    /**
     * @var ContentStatusRepositoryTest
     */
    protected $contentStatusRepositoryTest;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        try {
            $this->contentStatusService = $this->container->make(ContentStatusService::class);
            $this->contentStatusRepositoryTest = $this->container->make(ContentStatusRepositoryTest::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }

    }

    public function testGetById()
    {
        $contentServiceResponse = $this->testStore();
        $result = $this->container->call([$this->contentStatusService, 'getById'], ['id' => $contentServiceResponse->id]);
        self::assertTrue($result->status);
    }

    private function getDummy(int $number = 0): ContentStatusServiceRequest
    {
        $contentStatusRepositoryRequest = $this->container->call([$this->contentStatusRepositoryTest, 'getDummy'], ['no' => $number]);
        $contentStatusServiceRequest = null;
        try {
            $contentStatusServiceRequest = $this->container->make(ContentStatusServiceRequest::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }
        return Lazy::copy($contentStatusRepositoryRequest, $contentStatusServiceRequest);
    }

    public function testStore(int $number = 0)
    {
        $result = $this->container->call([$this->contentStatusRepositoryTest, 'testStore']);
        self::assertNotEquals(null, $result);
        return $result;
    }

    public function testGet()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(50, $this->getFaker()->numberBetween(50, 100)); $i++) {
            $this->testStore($i);
        }
        $result = $this->container->call([$this->contentStatusService, 'get']);
        self::assertTrue($result->status);
    }

    public function testGetContentStatus(): void
    {
        $resultContentStatuses = $this->getContainer()->call([$this->contentStatusService, "get"]);
        self::assertTrue($resultContentStatuses->status);
    }
}
