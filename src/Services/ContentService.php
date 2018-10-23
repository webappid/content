<?php

namespace WebAppId\Content\Services;

use WebAppId\Content\Requests\ContentRequest;
use WebAppId\Content\Repositories\ContentRepository;
use WebAppId\Content\Repositories\ContentCategoryRepository;
use WebAppId\Content\Repositories\CategoryRepository;
use WebAppId\Content\Repositories\TimeZoneRepository;
use WebAppId\Content\Repositories\ContentChildRepository;
use WebAppId\Content\Repositories\ContentGalleryRepository;
use Illuminate\Container\Container;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ContentService{
    private $container;

    public function __construct(Container $container){
        $this->container = $container;
    }

    private function getDefault($timeZone, $request){

        $user_id = Auth::id()==null?session('user_id'):Auth::id();

        if(session('timezone')==null){
            $zone = "Asia/Jakarta";
        }else{
            $zone = session('timezone');
        }

        

        $timeZoneData = $this->container->call([$timeZone,'getOneTimeZoneByName'],['name' => $zone]);

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

    public function store($request, ContentRepository $contentRepository, TimeZoneRepository $timeZoneRepository, ContentCategoryRepository $contentCategoryRepository, ContentChildRepository $contentChildRepository)
    {
        $request = $this->getDefault($timeZoneRepository, $request);
        
        $result['content'] = $this->container->call([$contentRepository,'addContent'],['data' => $request]);
        
        if(isset($request->parent_id)){
            $contentChildRequest = new \StdClass;
            $contentChildRequest->user_id = $request->user_id;
            $contentChildRequest->content_parent_id = $request->parent_id;
            $contentChildRequest->content_child_id = $result['content']->id;
            $result['content_child'] = $this->container->call([$contentChildRepository,'addContentChild'],['request'=>$contentChildRequest]);
            
        }

        $contentCategoryData = new \StdClass;
        $contentCategoryData->user_id = $request->user_id;
        $contentCategoryData->content_id = $result['content']->id;
        $contentCategoryData->categories_id = $request->category_id;
        $result['content_category'] = $this->container->call([$contentCategoryRepository,'addContentCategory'],['data' => $contentCategoryData]);
        
        return $result;
        
    }

    public function show(Request $request, ContentRepository $contentRepository, CategoryRepository $categoryRepository)
    {
        
        $categoryName = isset($request->category)?$request->category:$request->search['category'];
        $categoryData = $this->container->call([$categoryRepository,'getSearchOne'],['search' => $categoryName]);
        $search = isset($request->q)?$request->q:$request->search['value'];
        $result['content'] = $this->container->call([$contentRepository,'getAll'],['category_id' => $categoryData->id]);
		$result['recordsTotal'] = $this->container->call([$contentRepository,'getAllCount'],['category_id' => $categoryData->id]);
        $result['recordsFiltered'] = $this->container->call([$contentRepository,'getSearch'],['search' => $search, 'category_id' => $categoryData->id]);
        return $result;
    }

    public function edit($code, ContentRepository $contentRepository)
    {
        $result['content'] = $this->container->call([$contentRepository,'getContentByCode'],['code' => $code]);
        return $result;
    }

    public function update($code, $request, ContentRepository $contentRepository, TimeZoneRepository $timeZoneRepository)
    {

        $request = $this->getDefault($timeZoneRepository, $request);
        
        $result['content'] = $this->container->call([$contentRepository,'updateContentByCode'],['request' => $request, 'code' => $code]);

        return $result;
    }

    public function destroy($code, ContentRepository $contentRepository)
    {
        return $this->container->call([$contentRepository,'deleteContentByCode'],['code' => $code]);   
    }

    public function detail($code, ContentRepository $contentRepository, ContentGalleryRepository $contentGalleryRepository){
        
        $result['content'] = $this->container->call([$contentRepository,'getContentByCode'],['code' => $code]);
        dd($result);
        return $result;
    }
}