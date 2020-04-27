<?php


namespace WebAppId\Content\Services\Contracts;

use WebAppId\Content\Repositories\CategoryRepository;
use WebAppId\Content\Repositories\ContentCategoryRepository;
use WebAppId\Content\Repositories\ContentChildRepository;
use WebAppId\Content\Repositories\ContentGalleryRepository;
use WebAppId\Content\Repositories\ContentRepository;
use WebAppId\Content\Repositories\Requests\ContentRepositoryRequest;
use WebAppId\Content\Repositories\TimeZoneRepository;
use WebAppId\Content\Services\Requests\ContentChildServiceRequest;
use WebAppId\Content\Services\Requests\ContentServiceRequest;
use WebAppId\Content\Services\Requests\ContentServiceSearchRequest;
use WebAppId\Content\Services\Responses\ContentServiceResponse;
use WebAppId\Content\Services\Responses\ContentServiceResponseList;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 26/04/20
 * Time: 16.26
 * Interface ContentServiceContract
 * @package WebAppId\Content\Services\Contracts
 */
interface ContentServiceContract
{
    /**
     * @param ContentChildServiceRequest $contentChildServiceRequest
     * @param ContentServiceRequest $contentServiceRequest
     * @param ContentRepositoryRequest $contentRepositoryRequest
     * @param ContentRepository $contentRepository
     * @param TimeZoneRepository $timeZoneRepository
     * @param ContentCategoryRepository $contentCategoryRepository
     * @param ContentChildRepository $contentChildRepository
     * @param ContentGalleryRepository $contentGalleryRepository
     * @param ContentServiceResponse $contentServiceResponse
     * @return ContentServiceResponse
     */
    public function store(
        ContentChildServiceRequest $contentChildServiceRequest,
        ContentServiceRequest $contentServiceRequest,
        ContentRepositoryRequest $contentRepositoryRequest,
        ContentRepository $contentRepository,
        TimeZoneRepository $timeZoneRepository,
        ContentCategoryRepository $contentCategoryRepository,
        ContentChildRepository $contentChildRepository,
        ContentGalleryRepository $contentGalleryRepository,
        ContentServiceResponse $contentServiceResponse): ContentServiceResponse;

    /**
     * @param ContentServiceSearchRequest $contentServiceSearchRequest
     * @param ContentRepository $contentRepository
     * @param CategoryRepository $categoryRepository
     * @param ContentServiceResponseList $contentServiceResponseList
     * @return ContentServiceResponseList
     */
    public function get(ContentServiceSearchRequest $contentServiceSearchRequest,
                        ContentRepository $contentRepository,
                        CategoryRepository $categoryRepository,
                        ContentServiceResponseList $contentServiceResponseList): ContentServiceResponseList;

    /**
     * @param string $code
     * @param ContentServiceRequest $contentServiceRequest
     * @param ContentRepositoryRequest $contentRepositoryRequest
     * @param ContentRepository $contentRepository
     * @param TimeZoneRepository $timeZoneRepository
     * @param ContentCategoryRepository $contentCategoryRepository
     * @param ContentGalleryRepository $contentGalleryRepository
     * @param ContentServiceResponse $contentServiceResponse
     * @return ContentServiceResponse
     */
    public function update(string $code,
                           ContentServiceRequest $contentServiceRequest,
                           ContentRepositoryRequest $contentRepositoryRequest,
                           ContentRepository $contentRepository,
                           TimeZoneRepository $timeZoneRepository,
                           ContentCategoryRepository $contentCategoryRepository,
                           ContentGalleryRepository $contentGalleryRepository,
                           ContentServiceResponse $contentServiceResponse): ContentServiceResponse;

    /**
     * @param string $code
     * @param int $status
     * @param ContentRepository $contentRepository
     * @param ContentServiceResponse $contentServiceResponse
     * @return ContentServiceResponse
     */
    public function updateStatusByCode(string $code,
                                       int $status,
                                       ContentRepository $contentRepository,
                                       ContentServiceResponse $contentServiceResponse): ContentServiceResponse;

    /**
     * @param string $code
     * @param ContentRepository $contentRepository
     * @param ContentServiceResponse $contentServiceResponse
     * @return ContentServiceResponse|null
     */
    public function detail(string $code,
                           ContentRepository $contentRepository,
                           ContentServiceResponse $contentServiceResponse): ?ContentServiceResponse;
}
