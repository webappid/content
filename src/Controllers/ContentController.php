<?php

namespace WebAppId\Content\Controllers;

use WebAppId\Content\Controllers\Controller;
use WebAppId\Content\Models\Content AS ContentModel;
use WebAppId\Content\Models\ContentCategory AS ContentCategoryModel;
use WebAppId\Content\Models\Category AS CategoryModel;
use WebAppId\Content\Models\TimeZone AS TimeZoneModel;
use WebAppId\Content\Models\ContentChild AS ContentChildModel;
use WebAppId\Contents\Models\ContentGallery AS ContentGalleryModel;

use WebAppId\Content\Requests\ContentRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Carbon\Carbon;


abstract class ContentController extends Controller{

    protected abstract function indexResult();
	protected abstract function showResult($result);
	protected abstract function createResult();
	protected abstract function storeResult($result);
	protected abstract function editResult($result);
	protected abstract function updateResult($result);
    protected abstract function destroyResult($result);
    protected abstract function detailResult($result);

    /*
        set default for request
    */

    private function getDefault($timeZoneModel, $request){

        $user_id = Auth::id()==null?session('user_id'):Auth::id();

        if(session('timezone')==null){
            $zone = "Asia/Jakarta";
        }else{
            $zone = session('timezone');
        }
        $timeZoneData = $timeZoneModel->getOneTimeZoneByName($zone);

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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->indexResult();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->createResult();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContentRequest $request, ContentModel $contentModel, TimeZoneModel $timeZoneModel, ContentCategoryModel $contentCategoryModel, ContentChildModel $contentChildModel)
    {

        $request = $this->getDefault($timeZoneModel, $request);
        
        $result['content'] = $contentModel->addContent($request);

        if(isset($request->parent_id)){
            $contentChildRequest = new \StdClass;
            $contentChildRequest->user_id = $request->user_id;
            $contentChildRequest->content_parent_id = $request->parent_id;
            $contentChildRequest->content_child_id = $result['content']->id;
            $result['content_child'] = $contentChildModel->addContentChild($contentChildRequest);
        }

        $contentCategoryData = new \StdClass;
        $contentCategoryData->user_id = $request->user_id;
        $contentCategoryData->content_id = $result['content']->id;
        $contentCategoryData->categories_id = $request->category_id;
        $result['content_category'] = $contentCategoryModel->addContentCategory($contentCategoryData);
        return $this->storeResult($result);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, ContentModel $contentModel, CategoryModel $categoryModel)
    {
        
        $categoryName = isset($request->category)?$request->category:$request->search['category'];
        $categoryData = $categoryModel->getSearchOne($categoryName);
        $search = isset($request->q)?$request->q:$request->search['value'];
        $result['content'] = $contentModel->getAll($categoryData->id);
		$result['recordsTotal'] = $contentModel->getAllCount($categoryData->id);
        $result['recordsFiltered'] = $contentModel->getSearch($search, $categoryData->id);
        return $this->showResult($result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($code, ContentModel $contentModel)
    {
        $result['content'] = $contentModel->getContentByCode($code);
        return $this->editResult($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ContentRequest $request, $code, ContentModel $contentModel, TimeZoneModel $timeZoneModel)
    {

        $request = $this->getDefault($timeZoneModel, $request);
        
        $result['content'] = $contentModel->updateContentByCode($request, $code);

        return $this->updateResult($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($code, ContentModel $contentModel)
    {
        $result = $contentModel->deleteContentByCode($code);   
        return $this->destroyResult($result);
    }

    public function detail($code, ContentModel $contentModel, ContentGalleryModel $contentGallery){
        
        $result['content'] = $contentModel->getContentByCode($code);

        return $this->detailResult($result);
    }

}