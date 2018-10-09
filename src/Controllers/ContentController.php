<?php

namespace WebAppId\Content\Controllers\References;

use WebAppId\Content\Controllers\Controller;
use WebAppId\Content\Models\Content AS ContentModel;
use WebAppId\Content\Requests\ContentRequest;

use App\Http\Controllers\Content;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Carbon\Carbon;


class ContentController extends Controller{

    /*
        set default for request
    */

    private function getDefault(){
        $timeZoneData = $timeZone->getOneTimeZoneByName(session('timezone'));

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
        
        $request->user_id = Auth::id();

        $request->owner_id = Auth::id();

        return $request;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Content $content)
    {
        $content->indexResult();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Content $content)
    {
        $content->createResult();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContentRequest $request, ContentModel $contentModel, Content $content, Timezone $timeZone)
    {

        $request = $this->getDefault();

        $request->creator_id = Auth::id();

        $result = $contentModel->addContent($request);

        $content->storeResult($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, ContentModel $contentModel, Content $content)
    {
        $result['data']['data'] = $data;
		$result['data']['recordsTotal'] = $content->getAll($category_id)->count();
        $result['data']['recordsFiltered'] = $content->getSearch($request->search['value'], $category_id);
        
        $content->showResult($result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, ContentModel $contentModel, Content $content)
    {
        $content->editResult();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ContentRequest $request, $code, ContentModule $contentModule, Content $content)
    {
        $request = $this->getDefault();

        $result = $contentModule->updateContentByCode($request, $code);

        $content->updateResult($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, ContentModel $contentModel, Content $content)
    {
        $content->destroyResult();
    }
}