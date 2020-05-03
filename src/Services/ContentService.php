<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Services;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
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
use WebAppId\DDD\Services\BaseService;
use WebAppId\DDD\Tools\Lazy;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 25/04/20
 * Time: 20.52
 * Class ContentService
 * @package WebAppId\Content\Services
 */
class ContentService extends BaseService implements ContentServiceContract
{

    /**
     * @param TimeZoneRepository $timeZoneRepository
     * @param ContentServiceRequest $contentServiceRequest
     * @return ContentServiceRequest
     */
    private function getDefault(TimeZoneRepository $timeZoneRepository,
                                ContentServiceRequest $contentServiceRequest): ContentServiceRequest
    {

        $user_id = Auth::id() == null ? session('user_id') : Auth::id();

        if (session('timezone') == null) {
            $zone = "Asia/Jakarta";
        } else {
            $zone = session('timezone');
        }

        $contentServiceRequest->user_id = $user_id;

        $timeZoneData = $this->container->call([$timeZoneRepository, 'getByName'], ['name' => $zone]);


        $contentServiceRequest->code = str::slug($contentServiceRequest->title);

        if ($contentServiceRequest->keyword == null) {
            $contentServiceRequest->keyword = $contentServiceRequest->title;
        }

        if ($contentServiceRequest->og_title == null) {
            $contentServiceRequest->og_title = $contentServiceRequest->title . ' - ' . env('APP_NAME');
        }

        if ($contentServiceRequest->og_description == null) {
            $contentServiceRequest->og_description = $contentServiceRequest->description;
        }

        if ($contentServiceRequest->default_image == null) {
            $contentServiceRequest->default_image = 1;
        }

        if ($contentServiceRequest->status_id == null) {
            $contentServiceRequest->status_id = 1;
        }

        if ($contentServiceRequest->language_id == null) {
            $contentServiceRequest->language_id = 1;
        }

        if ($contentServiceRequest->publish_date == null) {
            $contentServiceRequest->publish_date = Carbon::now('UTC');
        }

        if ($contentServiceRequest->additional_info == null) {
            $contentServiceRequest->additional_info = "";
        }

        if ($contentServiceRequest->time_zone_id == null) {
            $contentServiceRequest->time_zone_id = isset($timeZoneData) ? $timeZoneData->id : 1;
        }

        if ($contentServiceRequest->creator_id == null) {
            $contentServiceRequest->creator_id = $contentServiceRequest->user_id;
        }

        if ($contentServiceRequest->owner_id == null) {
            $contentServiceRequest->owner_id = $contentServiceRequest->user_id;
        }

        return $contentServiceRequest;
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

        $contentServiceRequest = $this->getDefault($timeZoneRepository, $contentServiceRequest);

        $contentRepositoryRequest = Lazy::copy($contentServiceRequest, $contentRepositoryRequest);

        unset($contentRepositoryRequest->categories);

        unset($contentRepositoryRequest->parent_id);

        unset($contentRepositoryRequest->galleries);

        $content = $this->container->call([$contentRepository, 'store'], compact('contentRepositoryRequest'));

        if ($content == null) {
            $contentServiceResponse->status = false;
            $contentServiceResponse->message = 'Save content failed';
            return $contentServiceResponse;
        }

        $contentServiceResponse->content = $content;

        if ($contentServiceRequest->parent_id != null) {
            $contentChildServiceRequest->user_id = $contentRepositoryRequest->user_id;
            $contentChildServiceRequest->content_parent_id = $contentRepositoryRequest->parent_id;
            $contentChildServiceRequest->content_child_id = $content->id;
            $this->container->call([$contentChildRepository, 'store'], compact('contentChildServiceRequest'));
            if (count($content->parents) > 0) {
                $contentRepository->cleanCache($content->parents[0]['code']);
            }
        }

        $galleries = $contentServiceRequest->galleries;

        $galleries = $this->storeGalleries($galleries, $content, $contentServiceRequest, $contentGalleryRepository);

        $contentServiceResponse->galleries = $galleries;

        $categories = $contentServiceRequest->categories;

        $categories = $this->storeCategories($categories, $content, $contentServiceRequest, $contentCategoryRepository);

        $contentServiceResponse->status = true;

        $contentServiceResponse->message = 'store data success';

        $contentServiceResponse->categories = $categories;

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
            $contentCategoryRepositoryRequest = $this->container->make(ContentCategoryRepositoryRequest::class);
            $contentCategoryRepositoryRequest->user_id = $contentServiceRequest->user_id;
            $contentCategoryRepositoryRequest->content_id = $content->id;
            $contentCategoryRepositoryRequest->category_id = $category;
            $categories[] = $this->container->call([$contentCategoryRepository, 'store'], compact('contentCategoryRepositoryRequest'));
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
            $contentGalleryRepositoryRequest = $this->container->make(ContentGalleryRepositoryRequest::class);
            $contentGalleryRepositoryRequest->content_id = $content->id;
            $contentGalleryRepositoryRequest->user_id = $contentServiceRequest->user_id;
            $contentGalleryRepositoryRequest->file_id = $gallery;
            $contentGalleryRepositoryRequest->description = '';
            $galleries[] = $this->container->call([$contentGalleryRepository, 'store'], compact('contentGalleryRepositoryRequest'));
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

        $categoryName = $contentServiceSearchRequest->category == null ? '' : $contentServiceSearchRequest->category;

        $categoryResult = $this->container->call([$categoryRepository, 'getByName'], ['name' => $categoryName]);

        if ($categoryResult == null) {
            $categoryId = null;
        } else {
            $categoryId = $categoryResult->id;
        }

        $q = $contentServiceSearchRequest->q;

        $content = $this->container->call([$contentRepository, 'get'], ['q' => $q, 'category_id' => $categoryId]);

        if ($content != null) {
            $contentServiceResponseList->status = true;
            $contentServiceResponseList->contentList = $content;
            $recordsTotal = $this->container->call([$contentRepository, 'getCount'], ['category_id' => $categoryId]);
            $contentServiceResponseList->count = $recordsTotal;
            $recordsFiltered = $this->container->call([$contentRepository, 'getCount'], ['q' => $q, 'category_id' => $categoryId]);
            $contentServiceResponseList->countFiltered = $recordsFiltered;
        } else {
            $contentServiceResponseList->status = false;
        }

        return $contentServiceResponseList;
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

        $contentServiceRequest = $this->getDefault($timeZoneRepository, $contentServiceRequest);

        $contentRepositoryRequest = Lazy::copy($contentServiceRequest, $contentRepositoryRequest);

        unset($contentRepositoryRequest->categories);

        unset($contentRepositoryRequest->parent_id);

        unset($contentRepositoryRequest->galleries);

        $content = $this->container->call([$contentRepository, 'update'], compact('contentRepositoryRequest', 'code'));

        if ($content != null) {
            $this->container->call([$contentGalleryRepository, 'deleteByContentId'], ['contentId' => $content->id]);

            $galleries = $contentServiceRequest->galleries;

            $galleries = $this->storeGalleries($galleries, $content, $contentServiceRequest, $contentGalleryRepository);

            $contentServiceResponse->galleries = $galleries;

            $contentServiceResponse->content = $content;

            $categories = $contentServiceRequest->categories;

            $this->container->call([$contentCategoryRepository, 'deleteByContentId'], ['contentId' => $content->id]);

            $categories = $this->storeCategories($categories, $content, $contentServiceRequest, $contentCategoryRepository);


            $contentServiceResponse->categories = $categories;

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
     * @param string $code
     * @param ContentRepository $contentRepository
     * @return bool
     */
    public function destroy(string $code,
                            ContentRepository $contentRepository): bool
    {
        return $this->container->call([$contentRepository, 'delete'], ['code' => $code]);
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
            $content = $this->container->call([$contentRepository, 'getByCode'], ['code' => $code]);
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
        $result = $this->container->call([$contentRepository, 'updateStatusByCode'], ['code' => $code, 'status_id' => $status]);
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
