<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-01-19
 * Time: 00:01
 */

namespace WebAppId\Content\Services;


use WebAppId\Content\Services\Responses\ContentStatusServiceResponse;
use WebAppId\Content\Services\Responses\ContentStatusServiceResponseList;
use Illuminate\Database\Eloquent\Collection;
use WebAppId\Content\Models\ContentStatus;
use WebAppId\Content\Repositories\ContentStatusRepository;
use WebAppId\Content\Services\Contracts\ContentStatusServiceContract;
use WebAppId\DDD\Services\BaseService;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 25/04/20
 * Time: 14.51
 * Class ContentStatusService
 * @package WebAppId\Content\Services
 */
class ContentStatusService extends BaseService implements ContentStatusServiceContract
{
    /**
     * @inheritDoc
     */
    public function getById(int $id, ContentStatusRepository $contentStatusRepository, ContentStatusServiceResponse $contentStatusServiceResponse): ContentStatusServiceResponse
    {
        $result = $this->container->call([$contentStatusRepository, 'getById'], ['id' => $id]);
        if ($result != null) {
            $contentStatusServiceResponse->status = true;
            $contentStatusServiceResponse->message = 'Data Found';
            $contentStatusServiceResponse->contentStatus = $result;
        } else {
            $contentStatusServiceResponse->status = false;
            $contentStatusServiceResponse->message = 'Data Not Found';
        }

        return $contentStatusServiceResponse;
    }

    /**
     * @inheritDoc
     */
    public function get(ContentStatusRepository $contentStatusRepository, ContentStatusServiceResponseList $contentStatusServiceResponseList, int $length = 12): ContentStatusServiceResponseList
    {
        $result = $this->container->call([$contentStatusRepository, 'get']);

        if (count($result) > 0) {
            $contentStatusServiceResponseList->status = true;
            $contentStatusServiceResponseList->message = 'Data Found';
            $contentStatusServiceResponseList->contentStatusList = $result;
            $contentStatusServiceResponseList->count = $this->container->call([$contentStatusRepository, 'getCount']);
        } else {
            $contentStatusServiceResponseList->status = false;
            $contentStatusServiceResponseList->message = 'Data Not Found';
        }

        return $contentStatusServiceResponseList;
    }

    /**
     * @inheritDoc
     */
    public function getCount(ContentStatusRepository $contentStatusRepository): int
    {
        return $this->container->call([$contentStatusRepository, 'getCount']);
    }

    /**
     * @inheritDoc
     */
    public function getWhere(string $q, ContentStatusRepository $contentStatusRepository, ContentStatusServiceResponseList $contentStatusServiceResponseList, int $length = 12): ContentStatusServiceResponseList
    {
        $result = $this->container->call([$contentStatusRepository, 'getWhere'], ['q' => $q]);
        if (count($result) > 0) {
            $contentStatusServiceResponseList->status = true;
            $contentStatusServiceResponseList->message = 'Data Found';
            $contentStatusServiceResponseList->contentStatusList = $result;
            $contentStatusServiceResponseList->count = $this->container->call([$contentStatusRepository, 'getCount']);
            $contentStatusServiceResponseList->countFiltered = $this->container->call([$contentStatusRepository, 'getWhereCount'], ['q' => $q]);
        } else {
            $contentStatusServiceResponseList->status = false;
            $contentStatusServiceResponseList->message = 'Data Not Found';
        }
        return $contentStatusServiceResponseList;
    }

    /**
     * @inheritDoc
     */
    public function getWhereCount(string $q, ContentStatusRepository $contentStatusRepository): int
    {
        return $this->container->call([$contentStatusRepository, 'getWhereCount'], ['q' => $q]);
    }

    /**
     * @param ContentStatusRepository $contentStatusRepository
     * @return ContentStatus|null
     * @deprecated
     */
    public function getContentStatus(ContentStatusRepository $contentStatusRepository): ?Collection
    {
        return $this->getContainer()->call([$contentStatusRepository, 'getContentStatus']);
    }
}
