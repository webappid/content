<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Services\Contracts;

use WebAppId\Content\Repositories\ContentStatusRepository;
use WebAppId\Content\Services\Responses\ContentStatusServiceResponse;
use WebAppId\Content\Services\Responses\ContentStatusServiceResponseList;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 04:47:50
 * Time: 2020/04/22
 * Class ContentStatusServiceContract
 * @package WebAppId\Content\Services\Contracts
 */
interface ContentStatusServiceContract
{
    /**
     * @param int $id
     * @param ContentStatusRepository $contentStatusRepository
     * @param ContentStatusServiceResponse $contentStatusServiceResponse
     * @return ContentStatusServiceResponse
     */
    public function getById(int $id, ContentStatusRepository $contentStatusRepository, ContentStatusServiceResponse $contentStatusServiceResponse): ContentStatusServiceResponse;

    /**
     * @param ContentStatusRepository $contentStatusRepository
     * @param int $length
     * @param ContentStatusServiceResponseList $contentStatusServiceResponseList
     * @return ContentStatusServiceResponseList
     */
    public function get(ContentStatusRepository $contentStatusRepository, ContentStatusServiceResponseList $contentStatusServiceResponseList,int $length = 12): ContentStatusServiceResponseList;

    /**
     * @param ContentStatusRepository $contentStatusRepository
     * @return int
     */
    public function getCount(ContentStatusRepository $contentStatusRepository):int;

    /**
     * @param string $q
     * @param ContentStatusRepository $contentStatusRepository
     * @param ContentStatusServiceResponseList $contentStatusServiceResponseList
     * @param int $length
     * @return ContentStatusServiceResponseList
     */
    public function getWhere(string $q, ContentStatusRepository $contentStatusRepository, ContentStatusServiceResponseList $contentStatusServiceResponseList,int $length = 12): ContentStatusServiceResponseList;

    /**
     * @param string $q
     * @param ContentStatusRepository $contentStatusRepository
     * @return int
     */
    public function getWhereCount(string $q, ContentStatusRepository $contentStatusRepository):int;
}
