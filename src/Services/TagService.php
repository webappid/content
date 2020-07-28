<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Services;

use WebAppId\Content\Repositories\Requests\TagRepositoryRequest;
use WebAppId\Content\Repositories\TagRepository;
use WebAppId\Content\Services\Contracts\TagServiceContract;
use WebAppId\Content\Services\Requests\TagServiceRequest;
use WebAppId\Content\Services\Responses\TagServiceResponse;
use WebAppId\Content\Services\Responses\TagServiceResponseList;
use WebAppId\DDD\Services\BaseService;
use WebAppId\DDD\Tools\Lazy;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 17:09:16
 * Time: 2020/07/28
 * Class TagService
 * @package App\Services
 */
class TagService extends BaseService implements TagServiceContract
{

    /**
     * @inheritDoc
     */
    public function store(TagServiceRequest $tagServiceRequest, TagRepositoryRequest $tagRepositoryRequest, TagRepository $tagRepository, TagServiceResponse $tagServiceResponse): TagServiceResponse
    {
        $tagRepositoryRequest = Lazy::copy($tagServiceRequest, $tagRepositoryRequest, Lazy::AUTOCAST);

        $result = $this->container->call([$tagRepository, 'store'], ['tagRepositoryRequest' => $tagRepositoryRequest]);
        if ($result != null) {
            $tagServiceResponse->status = true;
            $tagServiceResponse->message = 'Store Data Success';
            $tagServiceResponse->tag = $result;
        } else {
            $tagServiceResponse->status = false;
            $tagServiceResponse->message = 'Store Data Failed';
        }

        return $tagServiceResponse;
    }

    /**
     * @inheritDoc
     */
    public function update(int $id, TagServiceRequest $tagServiceRequest, TagRepositoryRequest $tagRepositoryRequest, TagRepository $tagRepository, TagServiceResponse $tagServiceResponse): TagServiceResponse
    {
        $tagRepositoryRequest = Lazy::copy($tagServiceRequest, $tagRepositoryRequest, Lazy::AUTOCAST);

        $result = $this->container->call([$tagRepository, 'update'], ['id' => $id, 'tagRepositoryRequest' => $tagRepositoryRequest]);
        if ($result != null) {
            $tagServiceResponse->status = true;
            $tagServiceResponse->message = 'Update Data Success';
            $tagServiceResponse->tag = $result;
        } else {
            $tagServiceResponse->status = false;
            $tagServiceResponse->message = 'Update Data Failed';
        }

        return $tagServiceResponse;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id, TagRepository $tagRepository, TagServiceResponse $tagServiceResponse): TagServiceResponse
    {
        $result = $this->container->call([$tagRepository, 'getById'], ['id' => $id]);
        if ($result != null) {
            $tagServiceResponse->status = true;
            $tagServiceResponse->message = 'Data Found';
            $tagServiceResponse->tag = $result;
        } else {
            $tagServiceResponse->status = false;
            $tagServiceResponse->message = 'Data Not Found';
        }

        return $tagServiceResponse;
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id, TagRepository $tagRepository): bool
    {
        return $this->container->call([$tagRepository, 'delete'], ['id' => $id]);
    }

    /**
     * @inheritDoc
     */
    public function get(TagRepository $tagRepository, TagServiceResponseList $tagServiceResponseList, int $length = 12, string $q = null): TagServiceResponseList
    {
        $result = $this->container->call([$tagRepository, 'get'], ['q' => $q]);
        if (count($result) > 0) {
            $tagServiceResponseList->status = true;
            $tagServiceResponseList->message = 'Data Found';
            $tagServiceResponseList->tagList = $result;
            $tagServiceResponseList->count = $this->container->call([$tagRepository, 'getCount']);
            $tagServiceResponseList->countFiltered = $this->container->call([$tagRepository, 'getCount'], ['q' => $q]);
        } else {
            $tagServiceResponseList->status = false;
            $tagServiceResponseList->message = 'Data Not Found';
        }
        return $tagServiceResponseList;
    }

    /**
     * @inheritDoc
     */
    public function getCount(TagRepository $tagRepository, string $q = null): int
    {
        return $this->container->call([$tagRepository, 'getCount'], ['q' => $q]);
    }
}
