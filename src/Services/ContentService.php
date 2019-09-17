<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Services;

use Illuminate\Pagination\LengthAwarePaginator;
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
use WebAppId\Content\Repositories\TimeZoneRepository;
use WebAppId\Content\Responses\ContentResponse;
use WebAppId\Content\Responses\ContentSearchResponse;
use WebAppId\Content\Services\Params\AddContentCategoryParam;
use WebAppId\Content\Services\Params\AddContentChildParam;
use WebAppId\Content\Services\Params\AddContentGalleryParam;
use WebAppId\Content\Services\Params\AddContentParam;
use WebAppId\Content\Services\Params\ContentSearchParam;
use WebAppId\DDD\Services\BaseService;

/**
 * Class ContentService
 * @package WebAppId\Content\Services
 */
class ContentService extends BaseService
{

    /**
     * @param TimeZoneRepository $timeZoneRepository
     * @param AddContentParam $addContentParam
     * @return mixed
     */
    private function getDefault(TimeZoneRepository $timeZoneRepository, AddContentParam $addContentParam): AddContentParam
    {

        $user_id = Auth::id() == null ? session('user_id') : Auth::id();

        if (session('timezone') == null) {
            $zone = "Asia/Jakarta";
        } else {
            $zone = session('timezone');
        }

        $addContentParam->setUserId($user_id);

        $timeZoneData = $this->getContainer()->call([$timeZoneRepository, 'getOneTimeZoneByName'], ['name' => $zone]);

        $addContentParam->setCode(str::slug($addContentParam->getTitle()));

        if ($addContentParam->getKeyword() == null) {
            $addContentParam->setKeyword($addContentParam->getTitle());
        }

        if ($addContentParam->getOgTitle() == null) {
            $addContentParam->setOgTitle($addContentParam->getTitle() . ' - ' . env('APP_NAME'));
        }

        if ($addContentParam->getOgDescription() == null) {
            $addContentParam->setOgDescription($addContentParam->getDescription());
        }

        if ($addContentParam->getDefaultImage() == null) {
            $addContentParam->setDefaultImage(1);
        }

        if ($addContentParam->getStatusId() == null) {
            $addContentParam->setStatusId(1);
        }

        if ($addContentParam->getLanguageId() == null) {
            $addContentParam->setLanguageId(1);
        }

        if ($addContentParam->getPublishDate() == null) {
            $addContentParam->setPublishDate(Carbon::now('UTC'));
        }

        if ($addContentParam->getAdditionalInfo() == null) {
            $addContentParam->setAdditionalInfo("");
        }

        if ($addContentParam->getTimeZoneId() == null) {
            $addContentParam->setTimeZoneId(isset($timeZoneData) ? $timeZoneData->id : 1);
        }

        if ($addContentParam->getCreatorId() == null) {
            $addContentParam->setCreatorId($addContentParam->getUserId());
        }

        if ($addContentParam->getOwnerId() == null) {
            $addContentParam->setOwnerId($addContentParam->getUserId());
        }

        return $addContentParam;
    }

    /**
     * @param AddContentParam $addContentParam
     * @param ContentRepository $contentRepository
     * @param TimeZoneRepository $timeZoneRepository
     * @param ContentCategoryRepository $contentCategoryRepository
     * @param ContentChildRepository $contentChildRepository
     * @param ContentGalleryRepository $contentGalleryRepository
     * @param ContentResponse $contentResponse
     * @return ContentResponse|null
     */
    public function store(AddContentParam $addContentParam,
                          ContentRepository $contentRepository,
                          TimeZoneRepository $timeZoneRepository,
                          ContentCategoryRepository $contentCategoryRepository,
                          ContentChildRepository $contentChildRepository,
                          ContentGalleryRepository $contentGalleryRepository,
                          ContentResponse $contentResponse): ?ContentResponse
    {

        if ($addContentParam->getCategories() == null || count($addContentParam->getCategories()) == 0) {
            $contentResponse->setStatus(false);
            $contentResponse->setMessage("categories data required");
            return $contentResponse;
        }

        $request = $this->getDefault($timeZoneRepository, $addContentParam);


        $content = $this->getContainer()->call([$contentRepository, 'addContent'], ['addContentParam' => $request]);

        if ($content == null) {
            $contentResponse->setStatus(false);
            $contentResponse->setMessage('Save content failed');
            return $contentResponse;
        }

        $contentResponse->setContent($content);

        if ($addContentParam->getParentId() != null) {
            $contentChildRequest = new AddContentChildParam();
            $contentChildRequest->setUserId($request->getUserId());
            $contentChildRequest->setContentParentId($request->getParentId());
            $contentChildRequest->setContentChildId($content->id);
            $this->getContainer()->call([$contentChildRepository, 'addContentChild'], ['addContentChildParam' => $contentChildRequest]);
            if (count($content->parents) > 0) {
                $contentRepository->cleanCache($content->parents[0]['code']);
            }
        }

        $galleries = $request->getGalleries();
        if ($galleries == null) {
            $galleries = [];
        }

        $galleries = $this->storeGalleries($galleries, $content, $request, $contentGalleryRepository);

        $contentResponse->setGallery((object)$galleries);

        $categories = $request->getCategories();

        $categories = $this->storeCategories($categories, $content, $request, $contentCategoryRepository);

        $contentResponse->setStatus(true);

        $contentResponse->setMessage('store data success');

        $contentResponse->setCategories($categories);

        return $contentResponse;
    }

    /**
     * @param array $categories
     * @param Content $content
     * @param AddContentParam $request
     * @param ContentCategoryRepository $contentCategoryRepository
     * @return array
     */
    private function storeCategories(array $categories, Content $content, AddContentParam $request, ContentCategoryRepository $contentCategoryRepository): array
    {
        foreach ($categories as $category) {
            $contentCategoryData = new AddContentCategoryParam();
            $contentCategoryData->setUserId($request->getUserId());
            $contentCategoryData->setContentId($content->id);
            $contentCategoryData->setCategoryId($category);
            $categories[] = $this->getContainer()->call([$contentCategoryRepository, 'addContentCategory'], ['addContentCategoryParam' => $contentCategoryData]);
        }
        return $categories;
    }

    /**
     * @param array $galleries
     * @param Content $content
     * @param AddContentParam $request
     * @param ContentGalleryRepository $contentGalleryRepository
     * @return array
     */
    private function storeGalleries(array $galleries, Content $content, AddContentParam $request, ContentGalleryRepository $contentGalleryRepository): array
    {
        foreach ($galleries as $gallery) {
            $galleryData = new AddContentGalleryParam();
            $galleryData->setContentId($content->id);
            $galleryData->setUserId($request->getUserId());
            $galleryData->setFileId($gallery);
            $galleryData->setDescription('');
            $galleries[] = $this->getContainer()->call([$contentGalleryRepository, 'addContentGallery'], ['addContentGalleryParam' => $galleryData]);
        }
        return $galleries;
    }

    /**
     * @param ContentSearchParam $contentSearchParam
     * @param ContentRepository $contentRepository
     * @param CategoryRepository $categoryRepository
     * @param string $paginate
     * @return LengthAwarePaginator|null
     */
    public function showPaginate(ContentSearchParam $contentSearchParam,
                                 ContentRepository $contentRepository,
                                 CategoryRepository $categoryRepository,
                                 $paginate = '12'): ?LengthAwarePaginator
    {

        $categoryName = $contentSearchParam->getCategory();
        if ($categoryName == null) {
            $categoryName = '';
        }
        $search = $contentSearchParam->getQ();
        if ($search == null) {
            $search = '';
        }
        $categoryData = $this->getContainer()->call([$categoryRepository, 'getSearchOne'], ['search' => $categoryName]);

        return $this->getContainer()->call([$contentRepository, 'getSearchPaginate'], ['search' => $search, 'category_id' => $categoryData->id, 'paginate' => $paginate]);
    }

    /**
     * @param ContentSearchParam $contentSearchParam
     * @param ContentRepository $contentRepository
     * @param CategoryRepository $categoryRepository
     * @param ContentSearchResponse $contentSearchResponse
     * @return mixed
     */
    public function show(ContentSearchParam $contentSearchParam,
                         ContentRepository $contentRepository,
                         CategoryRepository $categoryRepository,
                         ContentSearchResponse $contentSearchResponse): ContentSearchResponse
    {

        $categoryName = $contentSearchParam->getCategory();
        if ($categoryName == null) {
            $categoryName = '';
        }
        $categoryData = $this->getContainer()->call([$categoryRepository, 'getSearchOne'], ['search' => $categoryName]);
        $search = $contentSearchParam->getQ();
        if ($search == null) {
            $search = '';
        }
        $content = $this->getContainer()->call([$contentRepository, 'getSearch'], ['search' => $search, 'category_id' => $categoryData->id]);
        if ($content != null) {
            $contentSearchResponse->setStatus(true);
            $contentSearchResponse->setData($content);
            $recordsTotal = $this->getContainer()->call([$contentRepository, 'getAllCount'], ['category_id' => $categoryData->id]);
            $contentSearchResponse->setRecordsTotal($recordsTotal);
            $recordsFiltered = $this->getContainer()->call([$contentRepository, 'getSearchCount'], ['search' => $search, 'category_id' => $categoryData->id]);
            $contentSearchResponse->setRecordsFiltered($recordsFiltered);
        } else {
            $contentSearchResponse->setStatus(false);
        }

        return $contentSearchResponse;
    }

    /**
     * @param string $code
     * @param ContentRepository $contentRepository
     * @return Content|null
     */
    public function edit(string $code,
                         ContentRepository $contentRepository): ?Content
    {
        return $this->getContainer()->call([$contentRepository, 'getContentByCode'], ['code' => $code]);
    }

    /**
     * @param string $code
     * @param AddContentParam $addContentParam
     * @param ContentRepository $contentRepository
     * @param TimeZoneRepository $timeZoneRepository
     * @param ContentCategoryRepository $contentCategoryRepository
     * @param ContentGalleryRepository $contentGalleryRepository
     * @param ContentResponse $contentResponse
     * @return ContentResponse|null
     */
    public function update(string $code,
                           AddContentParam $addContentParam,
                           ContentRepository $contentRepository,
                           TimeZoneRepository $timeZoneRepository,
                           ContentCategoryRepository $contentCategoryRepository,
                           ContentGalleryRepository $contentGalleryRepository,
                           ContentResponse $contentResponse): ?ContentResponse
    {

        $addContentParam = $this->getDefault($timeZoneRepository, $addContentParam);

        $content = $this->getContainer()->call([$contentRepository, 'updateContentByCode'], ['addContentParam' => $addContentParam, 'code' => $code]);

        if ($content != null) {
            $this->getContainer()->call([$contentGalleryRepository, 'deleteContentGalleryByContentId'], ['content_id' => $content->id]);

            $galleries = $addContentParam->getGalleries();
            if ($galleries == null) {
                $galleries = [];
            }

            $galleries = $this->storeGalleries($galleries, $content, $addContentParam, $contentGalleryRepository);

            $contentResponse->setGallery((object)$galleries);
            $contentResponse->setContent($content);

            $categories = $addContentParam->getCategories();

            $this->getContainer()->call([$contentCategoryRepository, 'deleteContentCategoryByContentId'], ['contentId' => $content->id]);

            $categories = $this->storeCategories($categories, $content, $addContentParam, $contentCategoryRepository);

            $contentResponse->setCategories($categories);

            $contentResponse->setStatus(true);

            if (count($content->parents) > 0) {
                $contentRepository->cleanCache($content->parents[0]['code']);
            }
        } else {
            $contentResponse->setStatus(false);
            $contentResponse->setMessage('update failed');
        }

        return $contentResponse;
    }

    /**
     * @param string $code
     * @param ContentRepository $contentRepository
     * @return bool
     */
    public function destroy(string $code,
                            ContentRepository $contentRepository): bool
    {
        return $this->getContainer()->call([$contentRepository, 'deleteContentByCode'], ['code' => $code]);
    }

    /**
     * @param string $code
     * @param ContentRepository $contentRepository
     * @param ContentResponse $contentResponse
     * @return ContentResponse|null
     */
    public function detail(string $code,
                           ContentRepository $contentRepository,
                           ContentResponse $contentResponse): ?ContentResponse
    {
        $contentResponse = Cache::rememberForever('content-' . $code, function () use ($contentRepository, $contentResponse, $code) {
            $content = $this->getContainer()->call([$contentRepository, 'getContentByCode'], ['code' => $code]);
            if ($content != null) {
                $contentResponse->setStatus(true);
                $contentResponse->setContent($content);
                for ($i = 0; $i < count($content->galleries); $i++) {
                    $content->galleries[$i]['mime_type'] = $content->galleries[$i]->mime->name;
                }
                $contentResponse->setGallery($content->galleries);
                $contentResponse->setChild($content->childs);
            } else {
                $contentResponse->setStatus(false);
            }
            return $contentResponse;
        });

        return $contentResponse;
    }

    /**
     * @param string $code
     * @param int $status
     * @param ContentRepository $contentRepository
     * @return Content|null
     */
    public function updateContentStatusByCode(string $code,
                                              int $status,
                                              ContentRepository $contentRepository): ?Content
    {
        return $this->getContainer()->call([$contentRepository, 'updateContentStatusByCode'], ['code' => $code, 'status_id' => $status]);
    }
}