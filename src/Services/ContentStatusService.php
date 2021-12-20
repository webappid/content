<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-01-19
 * Time: 00:01
 */

namespace WebAppId\Content\Services;


use Illuminate\Database\Eloquent\Collection;
use WebAppId\Content\Models\ContentStatus;
use WebAppId\Content\Repositories\ContentStatusRepository;
use WebAppId\Content\Services\Responses\ContentStatusServiceResponse;
use WebAppId\Content\Services\Responses\ContentStatusServiceResponseList;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 25/04/20
 * Time: 14.51
 * Class ContentStatusService
 * @package WebAppId\Content\Services
 */
class ContentStatusService
{
    /**
     * @inheritDoc
     */
    public function getById(int $id, ContentStatusRepository $contentStatusRepository, ContentStatusServiceResponse $contentStatusServiceResponse): ContentStatusServiceResponse
    {
        $result = app()->call([$contentStatusRepository, 'getById'], ['id' => $id]);
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
        $result = app()->call([$contentStatusRepository, 'get']);

        if (count($result) > 0) {
            $contentStatusServiceResponseList->status = true;
            $contentStatusServiceResponseList->message = 'Data Found';
            $contentStatusServiceResponseList->contentStatusList = $result;
            $contentStatusServiceResponseList->count = app()->call([$contentStatusRepository, 'getCount']);
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
        return app()->call([$contentStatusRepository, 'getCount']);
    }

    /**
     * @inheritDoc
     */
    public function getWhere(string $q, ContentStatusRepository $contentStatusRepository, ContentStatusServiceResponseList $contentStatusServiceResponseList, int $length = 12): ContentStatusServiceResponseList
    {
        $result = app()->call([$contentStatusRepository, 'getWhere'], ['q' => $q]);
        if (count($result) > 0) {
            $contentStatusServiceResponseList->status = true;
            $contentStatusServiceResponseList->message = 'Data Found';
            $contentStatusServiceResponseList->contentStatusList = $result;
            $contentStatusServiceResponseList->count = app()->call([$contentStatusRepository, 'getCount']);
            $contentStatusServiceResponseList->countFiltered = app()->call([$contentStatusRepository, 'getWhereCount'], ['q' => $q]);
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
        return app()->call([$contentStatusRepository, 'getWhereCount'], ['q' => $q]);
    }

    /**
     * @param ContentStatusRepository $contentStatusRepository
     * @return ContentStatus|null
     * @deprecated
     */
    public function getContentStatus(ContentStatusRepository $contentStatusRepository): ?Collection
    {
        return app()->call([$contentStatusRepository, 'getContentStatus']);
    }
}
