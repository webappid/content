<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Services;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Cache;
use WebAppId\Content\Models\Content;
use WebAppId\Content\Repositories\CategoryRepository;
use WebAppId\Content\Repositories\ContentCategoryRepository;
use WebAppId\Content\Repositories\ContentChildRepository;
use WebAppId\Content\Repositories\ContentGalleryRepository;
use WebAppId\Content\Repositories\ContentRepository;
use WebAppId\Content\Repositories\Requests\ContentCategoryRepositoryRequest;
use WebAppId\Content\Repositories\Requests\ContentGalleryRepositoryRequest;
use WebAppId\Content\Repositories\Requests\ContentRepositoryRequest;
use WebAppId\Content\Repositories\TimeZoneRepository;
use WebAppId\Content\Services\Contracts\ContentServiceContract;
use WebAppId\Content\Services\Requests\ContentChildServiceRequest;
use WebAppId\Content\Services\Requests\ContentServiceRequest;
use WebAppId\Content\Services\Requests\ContentServiceSearchRequest;
use WebAppId\Content\Services\Responses\ContentServiceResponse;
use WebAppId\Content\Services\Responses\ContentServiceResponseList;
use WebAppId\DDD\Tools\Lazy;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 25/04/20
 * Time: 20.52
 * Class ContentService
 * @package WebAppId\Content\Services
 */
class ContentService implements ContentServiceContract
{
    /**
     * @param ContentServiceRequest $contentServiceRequest
     * @param ContentRepositoryRequest $contentRepositoryRequest
     * @return ContentRepositoryRequest
     */
    protected function transformContent(ContentServiceRequest $contentServiceRequest, ContentRepositoryRequest $contentRepositoryRequest): ContentRepositoryRequest
    {
        $contentRepositoryRequest = Lazy::copy($contentServiceRequest, $contentRepositoryRequest);

        unset($contentRepositoryRequest->categories);

        unset($contentRepositoryRequest->parent_id);

        unset($contentRepositoryRequest->galleries);

        return $contentRepositoryRequest;
    }

    /**
     * @inheritDoc
     * @throws BindingResolutionException
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
        ContentServiceResponse $contentServiceResponse): ContentServiceResponse
    {

        if (count($contentServiceRequest->categories) == 0) {
            $contentServiceResponse->status = false;
            $contentServiceResponse->message = "categories data required";
            return $contentServiceResponse;
        }

        $contentRepositoryRequest = $this->transformContent($contentServiceRequest, $contentRepositoryRequest);

        $duplicateCode = app()->call([$contentRepository, 'getDuplicateTitle'], ['q' => $contentRepositoryRequest->code]);

        if ($duplicateCode > 0) {
            $contentRepositoryRequest->code .= $duplicateCode;
        }

        $content = app()->call([$contentRepository, 'store'], ['contentRepositoryRequest' => $contentRepositoryRequest]);

        if ($content->id == null) {
            $contentServiceResponse->status = false;
            $contentServiceResponse->message = 'Save content failed';
            return $contentServiceResponse;
        }

        $contentServiceResponse->content = $content;

        if ($contentServiceRequest->parent_id != null) {
            $contentChildServiceRequest->user_id = $contentRepositoryRequest->user_id;
            $contentChildServiceRequest->content_parent_id = $contentRepositoryRequest->parent_id;
            $contentChildServiceRequest->content_child_id = $content->id;
            app()->call([$contentChildRepository, 'store'], compact('contentChildServiceRequest'));
            if (count($content->parents) > 0) {
                $contentRepository->cleanCache($content->parents[0]['code']);
            }
        }

        $contentServiceResponse = $this->storeCategoryAndGallery($contentServiceResponse, $contentGalleryRepository, $contentCategoryRepository, $contentServiceRequest, $content);

        $contentServiceResponse->status = true;

        $contentServiceResponse->message = 'store data success';

        return $contentServiceResponse;
    }

    /**
     * @param ContentServiceResponse $contentServiceResponse
     * @param ContentGalleryRepository $contentGalleryRepository
     * @param ContentCategoryRepository $contentCategoryRepository
     * @param ContentServiceRequest $contentServiceRequest
     * @param Content $content
     * @return ContentServiceResponse
     * @throws BindingResolutionException
     */
    protected function storeCategoryAndGallery(ContentServiceResponse $contentServiceResponse,
                                               ContentGalleryRepository $contentGalleryRepository,
                                               ContentCategoryRepository $contentCategoryRepository,
                                               ContentServiceRequest $contentServiceRequest,
                                               Content $content): ContentServiceResponse
    {
        app()->call([$contentGalleryRepository, 'deleteByContentId'], ['contentId' => $content->id]);

        $galleries = $contentServiceRequest->galleries;

        $galleries = $this->storeGalleries($galleries, $content, $contentServiceRequest, $contentGalleryRepository);

        $contentServiceResponse->galleries = $galleries;

        $contentServiceResponse->content = $content;

        $categories = $contentServiceRequest->categories;

        app()->call([$contentCategoryRepository, 'deleteByContentId'], ['contentId' => $content->id]);

        $categoryResponse = $this->storeCategories($categories, $content, $contentServiceRequest, $contentCategoryRepository);

        $contentServiceResponse->categories = $content->categories;

        return $contentServiceResponse;
    }

    /**
     * @inheritDoc
     * @throws BindingResolutionException
     */
    public function update(string $code,
                           ContentServiceRequest $contentServiceRequest,
                           ContentRepositoryRequest $contentRepositoryRequest,
                           ContentRepository $contentRepository,
                           TimeZoneRepository $timeZoneRepository,
                           ContentCategoryRepository $contentCategoryRepository,
                           ContentGalleryRepository $contentGalleryRepository,
                           ContentServiceResponse $contentServiceResponse): ContentServiceResponse
    {
        if (count($contentServiceRequest->categories) == 0) {
            $contentServiceResponse->status = false;
            $contentServiceResponse->message = "categories data required";
            return $contentServiceResponse;
        }

        $contentRepositoryRequest = $this->transformContent($contentServiceRequest, $contentRepositoryRequest);

        $content = app()->call([$contentRepository, 'getByCode'], ['code' => $code]);

        if ($content == null) {
            $contentServiceResponse->status = false;
            $contentServiceResponse->message = "Content Not Found";
            return $contentServiceResponse;
        }

        $duplicateCode = app()->call([$contentRepository, 'getDuplicateTitle'], ['q' => $contentRepositoryRequest->code, 'id' => $content->id]);

        if ($duplicateCode > 0) {
            $contentRepositoryRequest->code .= $duplicateCode;
        }

        $content = app()->call([$contentRepository, 'update'], compact('contentRepositoryRequest', 'code'));

        if ($content != null) {

            $contentServiceResponse = $this->storeCategoryAndGallery($contentServiceResponse, $contentGalleryRepository, $contentCategoryRepository, $contentServiceRequest, $content);

            $contentServiceResponse->status = true;

            if (count($content->parents) > 0) {
                $contentRepository->cleanCache($content->parents[0]['code']);
            }
        } else {
            $contentServiceResponse->status = false;
            $contentServiceResponse->message = 'Update Failed';
        }

        return $contentServiceResponse;
    }

    /**
     * @param array $categories
     * @param Content $content
     * @param ContentServiceRequest $contentServiceRequest
     * @param ContentCategoryRepository $contentCategoryRepository
     * @return array
     * @throws BindingResolutionException
     */
    private function storeCategories(array $categories, Content $content,
                                     ContentServiceRequest $contentServiceRequest,
                                     ContentCategoryRepository $contentCategoryRepository): array
    {
        foreach ($categories as $category) {
            $contentCategoryRepositoryRequest = app()->make(ContentCategoryRepositoryRequest::class);
            $contentCategoryRepositoryRequest->user_id = $contentServiceRequest->user_id;
            $contentCategoryRepositoryRequest->content_id = $content->id;
            $contentCategoryRepositoryRequest->category_id = $category;
            $categories[] = app()->call([$contentCategoryRepository, 'store'], compact('contentCategoryRepositoryRequest'));
        }
        return $categories;
    }

    /**
     * @param array $galleries
     * @param Content $content
     * @param ContentServiceRequest $contentServiceRequest
     * @param ContentGalleryRepository $contentGalleryRepository
     * @return array
     * @throws BindingResolutionException
     */
    private function storeGalleries(array $galleries, Content $content, ContentServiceRequest $contentServiceRequest, ContentGalleryRepository $contentGalleryRepository): array
    {
        foreach ($galleries as $gallery) {
            $contentGalleryRepositoryRequest = app()->make(ContentGalleryRepositoryRequest::class);
            $contentGalleryRepositoryRequest->content_id = $content->id;
            $contentGalleryRepositoryRequest->user_id = $contentServiceRequest->user_id;
            $contentGalleryRepositoryRequest->file_id = $gallery;
            $contentGalleryRepositoryRequest->description = '';
            $galleries[] = app()->call([$contentGalleryRepository, 'store'], compact('contentGalleryRepositoryRequest'));
        }
        return $galleries;
    }

    /**
     * @inheritDoc
     */
    public function get(ContentServiceSearchRequest $contentServiceSearchRequest,
                        ContentRepository $contentRepository,
                        CategoryRepository $categoryRepository,
                        ContentServiceResponseList $contentServiceResponseList): ContentServiceResponseList
    {

        if (count($contentServiceSearchRequest->categories) > 0) {

            $categoryResult = app()->call([$categoryRepository, 'getWhereInName'], ['names' => $contentServiceSearchRequest->categories]);

            $categories = [];
            foreach ($categoryResult as $category) {
                $categories[] = $category->id;
            }

            $q = $contentServiceSearchRequest->q;

            $content = app()->call([$contentRepository, 'get'], ['q' => $q, 'categories' => $categories]);

            $recordsTotal = app()->call([$contentRepository, 'getCount'], ['categories' => $categories]);
            $recordsFiltered = app()->call([$contentRepository, 'getCount'], ['q' => $q, 'categories' => $categories]);
        } else {
            $categoryName = $contentServiceSearchRequest->category == null ? '' : $contentServiceSearchRequest->category;

            $categoryResult = app()->call([$categoryRepository, 'getByName'], ['name' => $categoryName]);

            if ($categoryResult == null) {
                $categoryId = null;
            } else {
                $categoryId = $categoryResult->id;
            }

            $q = $contentServiceSearchRequest->q;

            $content = app()->call([$contentRepository, 'get'], ['q' => $q, 'category_id' => $categoryId]);
            $recordsTotal = app()->call([$contentRepository, 'getCount'], ['category_id' => $categoryId]);
            $recordsFiltered = app()->call([$contentRepository, 'getCount'], ['q' => $q, 'category_id' => $categoryId]);
        }

        if ($content != null) {
            $contentServiceResponseList->status = true;
            $contentServiceResponseList->contentList = $content;
            $contentServiceResponseList->count = $recordsTotal;
            $contentServiceResponseList->countFiltered = $recordsFiltered;
        } else {
            $contentServiceResponseList->status = false;
        }

        return $contentServiceResponseList;
    }


    /**
     * @param string $code
     * @param ContentRepository $contentRepository
     * @return bool
     */
    public function destroy(string $code,
                            ContentRepository $contentRepository): bool
    {
        return app()->call([$contentRepository, 'delete'], ['code' => $code]);
    }

    /**
     * @param string $code
     * @param ContentRepository $contentRepository
     * @param ContentServiceResponse $contentServiceResponse
     * @return ContentServiceResponse|null
     */
    public function detail(string $code,
                           ContentRepository $contentRepository,
                           ContentServiceResponse $contentServiceResponse): ?ContentServiceResponse
    {
        $contentServiceResponse = Cache::rememberForever('content-' . $code, function () use ($contentRepository, $contentServiceResponse, $code) {
            $content = app()->call([$contentRepository, 'getByCode'], ['code' => $code]);
            if ($content != null) {
                $contentServiceResponse->status = true;
                $contentServiceResponse = $this->getContentDetailComplete($content, $contentServiceResponse);
            } else {
                $contentServiceResponse->status = false;
            }
            return $contentServiceResponse;
        });

        return $contentServiceResponse;
    }


    private function getContentDetailComplete(Content $content,
                                              ContentServiceResponse $contentServiceResponse): ContentServiceResponse
    {
        $contentServiceResponse->content = $content;
        for ($i = 0; $i < count($content->galleries); $i++) {
            $content->galleries[$i]['mime_type'] = $content->galleries[$i]->mime->name;
        }
        $contentServiceResponse->galleries = $content->galleries;
        $contentServiceResponse->children = $content->childs;
        return $contentServiceResponse;
    }

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
                                       ContentServiceResponse $contentServiceResponse): ContentServiceResponse
    {
        $result = app()->call([$contentRepository, 'updateStatusByCode'], ['code' => $code, 'status_id' => $status]);
        if ($result != null) {
            $contentServiceResponse->status = true;
            $contentServiceResponse->message = "Update Status Success";
            $contentServiceResponse = $this->getContentDetailComplete($result, $contentServiceResponse);
        } else {
            $contentServiceResponse->status = false;
            $contentServiceResponse->message = "Update Status Failed";
        }
        return $contentServiceResponse;
    }
}
