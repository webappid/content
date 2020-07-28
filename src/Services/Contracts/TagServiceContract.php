<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Services\Contracts;

use WebAppId\Content\Repositories\Requests\TagRepositoryRequest;
use WebAppId\Content\Repositories\TagRepository;
use WebAppId\Content\Services\Requests\TagServiceRequest;
use WebAppId\Content\Services\Responses\TagServiceResponse;
use WebAppId\Content\Services\Responses\TagServiceResponseList;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 17:09:16
 * Time: 2020/07/28
 * Class TagServiceContract
 * @package WebAppId\Content\Services\Contracts
 */
interface TagServiceContract
{
    /**
     * @param TagServiceRequest $tagServiceRequest
     * @param TagRepositoryRequest $tagRepositoryRequest
     * @param TagRepository $tagRepository
     * @param TagServiceResponse $tagServiceResponse
     * @return TagServiceResponse
     */
    public function store(TagServiceRequest $tagServiceRequest, TagRepositoryRequest $tagRepositoryRequest, TagRepository $tagRepository, TagServiceResponse $tagServiceResponse): TagServiceResponse;

    /**
     * @param int $id
     * @param TagServiceRequest $tagServiceRequest
     * @param TagRepositoryRequest $tagRepositoryRequest
     * @param TagRepository $tagRepository
     * @param TagServiceResponse $tagServiceResponse
     * @return TagServiceResponse
     */
    public function update(int $id, TagServiceRequest $tagServiceRequest, TagRepositoryRequest $tagRepositoryRequest, TagRepository $tagRepository, TagServiceResponse $tagServiceResponse): TagServiceResponse;

    /**
     * @param int $id
     * @param TagRepository $tagRepository
     * @param TagServiceResponse $tagServiceResponse
     * @return TagServiceResponse
     */
    public function getById(int $id, TagRepository $tagRepository, TagServiceResponse $tagServiceResponse): TagServiceResponse;

    /**
     * @param int $id
     * @param TagRepository $tagRepository
     * @return bool
     */
    public function delete(int $id, TagRepository $tagRepository): bool;

    /**
     * @param string $q
     * @param TagRepository $tagRepository
     * @param TagServiceResponseList $tagServiceResponseList
     * @param int $length
     * @return TagServiceResponseList
     */
    public function get(TagRepository $tagRepository, TagServiceResponseList $tagServiceResponseList, int $length = 12, string $q = null): TagServiceResponseList;

    /**
     * @param string $q
     * @param TagRepository $tagRepository
     * @return int
     */
    public function getCount(TagRepository $tagRepository, string $q = null): int;
}
