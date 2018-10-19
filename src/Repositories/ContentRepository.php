<?php

namespace WebAppId\Content\Repositories;

use WebAppId\Content\Models\Content;

class ContentRepository
{
    /**
     * Method To Add Data Content
     *
     * @param Request $data
     * @return Boolean true/false
     */
    public function addContent($data, Content $content)
    {
        try {
            $content->title           = $data->title;
            $content->code            = $data->code;
            $content->description     = $data->description;
            $content->keyword         = $data->keyword;
            $content->og_title        = $data->og_title;
            $content->og_description  = $data->og_description;
            $content->default_image   = $data->default_image;
            $content->status_id       = $data->status_id;
            $content->language_id     = $data->language_id;
            $content->publish_date    = $data->publish_date;
            $content->additional_info = $data->additional_info;
            $content->content         = $data->content;
            $content->time_zone_id    = $data->time_zone_id;
            $content->owner_id        = $data->owner_id;
            $content->user_id         = $data->user_id;
            $content->creator_id      = $data->creator_id;
            $content->save();

            return $content;
        } catch (QueryException $e) {
            report($e);
            return false;
        }
    }

    public function updateContentByCode($request, $code, Content $content)
    {
        $result = $this->getContentByCode($code, $content);
        if ($result != null) {
            try {
                $result->title = $request->title;
                $result->code = $request->code;
                $result->description = $request->description;
                $result->keyword = $request->keyword;
                $result->og_title = $request->og_title;
                $result->og_description = $request->og_description;
                $result->default_image = $request->default_image;
                $result->status_id = $request->status_id;
                $result->language_id = $request->language_id;
                $result->publish_date = $request->publish_date;
                $result->additional_info = $request->additional_info;
                $result->content = $request->content;
                $result->time_zone_id = $request->time_zone_id;
                $result->owner_id = $request->owner_id;
                $result->user_id = $request->user_id;
                $result->save();
                return $result;
            } catch (QueryException $e) {
                report($e);
                return false;
            }
        } else {
            return false;
        }
    }

    private function getQueryAllByCategory($category_id, $content){
        return $content
        ->leftJoin('content_categories AS cc','contents.id','=','cc.content_id')
        ->when($category_id != null, function ($query) use ($category_id) {
            return $query->where('cc.categories_id','=',$category_id);
        });
    }

    /**
     * Get All Content
     *
     * @return Content $data
     */
    public function getAll($category_id = null, Content $content){
        return $this->getQueryAllByCategory($category_id, $content)->get();
    }

    public function getAllCount($category_id = null, Content $content){
        return $this->getQueryAllByCategory($category_id, $content)->count();
    }

    public function getContentByCode($code, Content $content)
    {
        return $content->where('code', $code)->first();
    }

    public function getDataForSearch($search="", $category_id, Content $content){
        $result = $content
            ->select(
                'contents.id AS id',
                'contents.code AS code',
                'contents.title AS title',
                'contents.description',
                'contents.keyword',
                'contents.og_title',
                'contents.og_description',
                'contents.default_image',
                'contents.language_id',
                'contents.status_id',
                'contents.publish_date',
                'contents.additional_info',
                'contents.content',
                'contents.owner_id',
                'contents.user_id',
                'time_zones.minute',
                'time_zones.name AS time_zone_name',
                'status.name AS status_name'
            )
            ->leftJoin('content_statuses AS status','contents.status_id','=','status.id')
            ->leftJoin('languages AS lang','contents.language_id','=','lang.id')
            ->leftJoin('content_categories AS cc','contents.id','=','cc.content_id')
            ->leftJoin('time_zones', 'time_zones.id','=','contents.time_zone_id')
            ->where('cc.categories_id','=',$category_id)
            ->where(function ($query) use ($search, $category_id) {
                $query
                    ->where('contents.title','LIKE','%'.$search.'%')
                    ->orWhere('contents.code','LIKE','%'.$search.'%')
                    ->orWhere('contents.description','LIKE','%'.$search.'%')
                    ->orWhere('contents.keyword','LIKE','%'.$search.'%')
                    ->orWhere('contents.og_title','LIKE','%'.$search.'%')
                    ->orWhere('contents.og_description','LIKE','%'.$search.'%')
                    ->orWhere('contents.language_id','LIKE','%'.$search.'%')
                    ->orWhere('contents.status_id','LIKE','%'.$search.'%')
                    ->orWhere('contents.publish_date','LIKE','%'.$search.'%')
                    ->orWhere('contents.additional_info','LIKE','%'.$search.'%')
                    ->orWhere('contents.content','LIKE','%'.$search.'%')
                    ->orWhere('contents.owner_id','LIKE','%'.$search.'%')
                    ->orWhere('contents.user_id','LIKE','%'.$search.'%');
            });
        return $result;
    }

    public function getSearch($search="", $category_id, Content $content)
    {
        return $this
            ->getDataForSearch($search="", $category_id)
            ->get();
    }

    public function getSearchCount($search="", $category_id, Content $content)
    {
        return $this
            ->getDataForSearch($search="", $category_id)
            ->count();
    }

    public function deleteContentByCode($code, Content $content){
        $content = $this->getContentByCode($code, $content);
        if($content!=null){
            try{
                $content->delete();
                return true;
            }catch(QueryException $e){
                report($e);
                return false;
            }
        }else{
            return false;
        }
    }

    public function udpateContentStatusByCode($code, $statusId, Content $content){
        $content = $this->getContentByCode($code, $content);
        if($content!=null){
            try{
                $content->status_id = $statusId;
                $content->save();
            }catch(QueryException $e){
                report($e);
                return false;
            }
        }
    }

    public function getQueryContentByKeyword($keyword, $content){
        return $content::where('keyword', $keyword);
    }

    public function getContentByKeyword($keyword, Content $content){
        return $this->getQueryContentByKeyword($keyword, $content)->get();
    }

    public function getContentByKeywordCount($keyword, Content $content){
        return $this->getQueryContentByKeyword($keyword, $content)->count();
    }

    public function getCategory(Content $content){
        return $content->category;
    }

    public function getContent(Content $content){
        return $content;
    }
}