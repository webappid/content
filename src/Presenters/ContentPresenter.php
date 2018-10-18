<?php

namespace WebAppId\Content\Presenters;

use WebAppId\Content\Requests\ContentRequest;
use WebAppId\Content\Repositories\ContentRepository AS ContentRepository;
use WebAppId\Content\Repositories\ContentCategoryRepository AS ContentCategoryRepository;
use WebAppId\Content\Repositories\CategoryRepository AS CategoryRepository;
use WebAppId\Content\Repositories\TimeZoneRepository AS TimeZoneRepository;
use WebAppId\Content\Repositories\ContentChildRepository AS ContentChildRepository;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ContentPresenter{
    private function getDefault($timeZone, $request){

        $user_id = Auth::id()==null?session('user_id'):Auth::id();

        if(session('timezone')==null){
            $zone = "Asia/Jakarta";
        }else{
            $zone = session('timezone');
        }

        $timeZoneData = $timeZone->getOneTimeZoneByName($zone);

        $request->code = str::slug($request->title);

        $request->keyword = isset($request->keyword)?$request->keyword:$request->title;

        $request->og_title = isset($request->og_title)?$request->og_title:$request->title . ' - ' . env('APP_NAME');

        $request->og_description = isset($request->og_description)?$request->og_description:$request->description;

        $request->default_image = isset($request->default_image)?$request->default_image:1;

        $request->status_id = isset($request->status_id)?$request->status_id:1;

        $request->language_id = isset($request->language_id)?$request->language_id:1;

        $request->publish_date = isset($request->publish_date)?$request->publish_date:Carbon::now('UTC');

        $request->additional_info = isset($request->additional_info)?$request->additional_info:Carbon::now('UTC');

        $request->additional_info = isset($request->additional_info)?$request->additional_info:Carbon::now('UTC');

        $request->time_zone_id = isset($timeZoneData)?$timeZoneData->id:1;
        
        $request->creator_id = $user_id;

        $request->user_id = $user_id;

        $request->owner_id = $user_id;

        return $request;
    }

    public function store(ContentRequest $request, ContentRepository $contentRepository, TimeZoneRepository $timeZoneRepository, ContentCategoryRepository $contentCategoryRepository, ContentChildRepository $contentChildRepository)
    {

        $request = $this->getDefault($timeZoneRepository, $request);
        
        $result['content'] = $contentRepository->addContent($request);
        
        if(isset($request->parent_id)){
            $contentChildRequest = new \StdClass;
            $contentChildRequest->user_id = $request->user_id;
            $contentChildRequest->content_parent_id = $request->parent_id;
            $contentChildRequest->content_child_id = $result['content']->id;
            $result['content_child'] = $contentChildRepository->addContentChild($contentChildRequest);
            
        }

        $contentCategoryData = new \StdClass;
        $contentCategoryData->user_id = $request->user_id;
        $contentCategoryData->content_id = $result['content']->id;
        $contentCategoryData->categories_id = $request->category_id;
        $result['content_category'] = $contentCategoryRepository->addContentCategory($contentCategoryData);
        
        return $result;
        
    }

    public function show(Request $request, ContentRepository $contentRepository, CategoryRepository $categoryRepository)
    {
        
        $categoryName = isset($request->category)?$request->category:$request->search['category'];
        $categoryData = $categoryRepository->getSearchOne($categoryName);
        $search = isset($request->q)?$request->q:$request->search['value'];
        $result['content'] = $contentRepository->getAll($categoryData->id);
		$result['recordsTotal'] = $contentRepository->getAllCount($categoryData->id);
        $result['recordsFiltered'] = $contentRepository->getSearch($search, $categoryData->id);
        return $result;
    }

    public function edit($code, ContentRepository $contentRepository)
    {
        $result['content'] = $contentRepository->getContentByCode($code);
        return $result;
    }

    public function update(ContentRequest $request, $code, ContentRepository $contentRepository, TimeZoneRepository $timeZoneRepository)
    {

        $request = $this->getDefault($timeZoneRepository, $request);
        
        $result['content'] = $contentRepository->updateContentByCode($request, $code);

        return $result;
    }

    public function destroy($code, ContentRepository $contentRepository)
    {
        $result = $contentRepository->deleteContentByCode($code);   
        return $result;
    }

    public function detail($code, ContentRepository $contentRepository, ContentGalleryRepository $contentGallery){
        
        $result['content'] = $contentRepository->getContentByCode($code);

        return $result;
    }
}