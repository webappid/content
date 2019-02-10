<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Services;

use Illuminate\Container\Container;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
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

/**
 * Class ContentService
 * @package WebAppId\Content\Services
 */
class ContentService
{
    private $container;
    
    /**
     * ContentService constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
    
    /**
     * @param TimeZoneRepository $timeZoneRepository
     * @param FormRequest $request
     * @return mixed
     */
    private function getDefault(TimeZoneRepository $timeZoneRepository, FormRequest $request)
    {
        
        $user_id = Auth::id() == null ? session('user_id') : Auth::id();
        
        if (session('timezone') == null) {
            $zone = "Asia/Jakarta";
        } else {
            $zone = session('timezone');
        }
    
    
        $timeZoneData = $this->container->call([$timeZoneRepository, 'getOneTimeZoneByName'], ['name' => $zone]);
        
        $request->code = str::slug($request->title);
        
        $request->keyword = isset($request->keyword) ? $request->keyword : $request->title;
        
        $request->og_title = isset($request->og_title) ? $request->og_title : $request->title . ' - ' . env('APP_NAME');
        
        $request->og_description = isset($request->og_description) ? $request->og_description : $request->description;
        
        $request->default_image = isset($request->default_image) ? $request->default_image : 1;
        
        $request->status_id = isset($request->status_id) ? $request->status_id : 1;
        
        $request->language_id = isset($request->language_id) ? $request->language_id : 1;
        
        $request->publish_date = isset($request->publish_date) ? $request->publish_date : Carbon::now('UTC');
    
        $request->additional_info = isset($request->additional_info) ? $request->additional_info : "";
        
        $request->time_zone_id = isset($timeZoneData) ? $timeZoneData->id : 1;
        
        $request->creator_id = $user_id;
        
        $request->user_id = $user_id;
        
        $request->owner_id = $user_id;
        
        return $request;
    }
    
    /**
     * @param FormRequest $request
     * @param ContentRepository $contentRepository
     * @param TimeZoneRepository $timeZoneRepository
     * @param ContentCategoryRepository $contentCategoryRepository
     * @param ContentChildRepository $contentChildRepository
     * @param ContentGalleryRepository $contentGalleryRepository
     * @param ContentResponse $contentResponse
     * @return ContentResponse|null
     */
    public function store(FormRequest $request,
                          ContentRepository $contentRepository,
                          TimeZoneRepository $timeZoneRepository,
                          ContentCategoryRepository $contentCategoryRepository,
                          ContentChildRepository $contentChildRepository,
                          ContentGalleryRepository $contentGalleryRepository,
                          ContentResponse $contentResponse): ?ContentResponse
    {
    
        if (!isset($request->categories)) {
            $contentResponse->setStatus(false);
            $contentResponse->setMessage("categories data required");
            return $contentResponse;
        }
        
        $request = $this->getDefault($timeZoneRepository, $request);
    
        $content = $this->container->call([$contentRepository, 'addContent'], ['data' => $request]);
        
        if (isset($request->parent_id)) {
            $contentChildRequest = new \StdClass;
            $contentChildRequest->user_id = $request->user_id;
            $contentChildRequest->content_parent_id = $request->parent_id;
            $contentChildRequest->content_child_id = $content['content']->id;
            $child = $this->container->call([$contentChildRepository, 'addContentChild'], ['request' => $contentChildRequest]);
            $contentResponse->setChild($child);
        }
    
        if (isset($request->galleries)) {
            $galleries = $request->galleries;
            for ($i = 0; $i < count($galleries); $i++) {
                $galleryData = new \StdClass();
                $galleryData->content_id = $content['content']->id;
                $galleryData->user_id = $request->user_id;
                $galleryData->file_id = $galleries[$i];
                $galleryData->description = "";
                $galleries[] = $this->container->call([$contentGalleryRepository, 'addContentGallery'], ['request' => $galleryData]);
            }
            $contentResponse->setGallery($galleries);
        }
    
        $categories = $request->categories;
    
        for ($i = 0; $i < count($categories); $i++) {
            $contentCategoryData = new \StdClass;
            $contentCategoryData->user_id = $request->user_id;
            $contentCategoryData->content_id = $content['content']->id;
            $contentCategoryData->categories_id = $categories[$i];
            $categories[] = $this->container->call([$contentCategoryRepository, 'addContentCategory'], ['data' => $contentCategoryData]);
        }
    
        $contentResponse->setCategories($categories);
    
        return $contentResponse;
    }
    
    /**
     * @param Request $request
     * @param ContentRepository $contentRepository
     * @param CategoryRepository $categoryRepository
     * @param string $paginate
     * @return object|null
     */
    public function showPaginate(Request $request,
                                 ContentRepository $contentRepository,
                                 CategoryRepository $categoryRepository,
                                 $paginate = '12'): ?object
    {
        
        $categoryName = isset($request->category) ? $request->category : $request->search['category'];
        $categoryData = $this->container->call([$categoryRepository, 'getSearchOne'], ['search' => $categoryName]);
        $search = isset($request->q) ? $request->q : $request->search['value'];
        return $this->container->call([$contentRepository, 'getSearchPaginate'], ['search' => $search, 'category_id' => $categoryData->id, 'paginate' => $paginate]);
    }
    
    /**
     * @param Request $request
     * @param ContentRepository $contentRepository
     * @param CategoryRepository $categoryRepository
     * @param ContentSearchResponse $contentSearchResponse
     * @return mixed
     */
    public function show(Request $request,
                         ContentRepository $contentRepository,
                         CategoryRepository $categoryRepository,
                         ContentSearchResponse $contentSearchResponse): ContentSearchResponse
    {
        
        $categoryName = isset($request->category) ? $request->category : $request->search['category'];
        $categoryData = $this->container->call([$categoryRepository, 'getSearchOne'], ['search' => $categoryName]);
        $search = isset($request->q) ? $request->q : $request->search['value'];
        $content = $this->container->call([$contentRepository, 'getSearch'], ['search' => $search, 'category_id' => $categoryData->id]);
        if ($content != null) {
            $contentSearchResponse->setStatus(true);
            $contentSearchResponse->setData($content);
            $recordsTotal = $this->container->call([$contentRepository, 'getAllCount'], ['category_id' => $categoryData->id]);
            $contentSearchResponse->setRecordsTotal($recordsTotal);
            $recordsFiltered = $this->container->call([$contentRepository, 'getSearchCount'], ['search' => $search, 'category_id' => $categoryData->id]);
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
        return $this->container->call([$contentRepository, 'getContentByCode'], ['code' => $code]);
    }
    
    /**
     * @param string $code
     * @param $request
     * @param ContentRepository $contentRepository
     * @param TimeZoneRepository $timeZoneRepository
     * @return Content|null
     */
    public function update(string $code,
                           $request,
                           ContentRepository $contentRepository,
                           TimeZoneRepository $timeZoneRepository): ?Content
    {
        
        $request = $this->getDefault($timeZoneRepository, $request);
    
        return $this->container->call([$contentRepository, 'updateContentByCode'], ['request' => $request, 'code' => $code]);
    }
    
    /**
     * @param string $code
     * @param ContentRepository $contentRepository
     * @return bool
     */
    public function destroy(string $code,
                            ContentRepository $contentRepository): bool
    {
        return $this->container->call([$contentRepository, 'deleteContentByCode'], ['code' => $code]);
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
        $content = $this->container->call([$contentRepository, 'getContentByCode'], ['code' => $code]);
        $contentResponse->setStatus(true);
        $contentResponse->setContent($content);
        $contentResponse->setGallery($content['content']->galleries);
        $contentResponse->setChild($content['content']->childs);
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
        return $this->container->call([$contentRepository, 'updateContentStatusByCode'], ['code' => $code, 'status_id' => $status]);
    }
}