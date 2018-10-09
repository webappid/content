<?php

namespace WebAppId\Content\Models;

use WebAppId\Content\Models\Content;
use WebAppId\Content\Models\ContentGallery;
use WebAppId\Content\Models\ContentStatus;
use WebAppId\Content\Models\ContentCategory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class Content extends Model
{
    //
    protected $table = 'contents';

    protected $hidden = ['created_at', 'updated_at', 'owner_id', 'user_id'];

    protected $fillable = ['id', 'code', 'title', 'description', 'keyword', 'og_title', 'og_description', 'default_image', ' status_id', 'language_id', 'publish_date', 'additional_info', 'content'];

    /**
     * Method To Add Data Content
     *
     * @param Request $data
     * @return Boolean true/false
     */
    public function addContent($data)
    {
        try {
            $result                  = new self();
            $result->title           = $data->title;
            $result->code            = $data->code;
            $result->description     = $data->description;
            $result->keyword         = $data->keyword;
            $result->og_title        = $data->og_title;
            $result->og_description  = $data->og_description;
            $result->default_image   = $data->default_image;
            $result->status_id       = $data->status_id;
            $result->language_id     = $data->language_id;
            $result->publish_date    = $data->publish_date;
            $result->additional_info = $data->additional_info;
            $result->content         = $data->content;
            $result->time_zone_id    = $data->time_zone_id;
            $result->owner_id        = $data->owner_id;
            $result->user_id         = $data->user_id;
            $result->creator_id      = $data->creator_id;
            $result->save();

            return $result;
        } catch (QueryException $e) {
            report($e);
            return false;
        }
    }

    public function updateContentByCode($request, $code)
    {
        $result = $this->getContentByCode($code);
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
                return true;
            } catch (QueryException $e) {
                report($e);
                return false;
            }
        } else {
            return false;
        }
    }

    private function getQueryAllByCategory(){
        return $this
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
    public function getAll($category_id = null){
        return $this->getQueryAllByCategory($category_id)->get();
    }

    public function getAllCount($category_id = null){
        return $this->getQueryAllByCategory($category_id)->count();
    }

    public function getContentByCode($code)
    {
        return $this->where('code', $code)->first();
    }

    public function getDataForSearch($search="", $category_id){
        $result = $this
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
                    ->orWhere('contents.venue','LIKE','%'.$search.'%')
                    ->orWhere('contents.additional_info','LIKE','%'.$search.'%')
                    ->orWhere('contents.content','LIKE','%'.$search.'%')
                    ->orWhere('contents.owner_id','LIKE','%'.$search.'%')
                    ->orWhere('contents.user_id','LIKE','%'.$search.'%');
            });
        return $result;
    }

    public function getSearch($search="", $category_id)
    {
        return $this
            ->getDataForSearch($search="", $category_id)
            ->get();
    }

    public function getSearchCount($search="", $category_id)
    {
        return $this
            ->getDataForSearch($search="", $category_id)
            ->count();
    }

    public function deleteContentByCode($code){
        $content = $this->getContentByCode($code);
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

    public function udpateContentStatusByCode($code, $statusId){
        $content = $this->getContentByCode($code);
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

    public function child(){
        return $this->belongsToMany(Content::class, 'content_childs', 'content_parent_id','content_child_id');
    }

    public function parent(){
        return $this->belongsToMany(Content::class, 'content_childs', 'content_child_id','content_parent_id');
    }

    public function category(){
        return $this->belongsToMany(Category::class, 'content_categories', 'content_id','categories_id');
    }

    public function gallery(){
        return $this->hasMany(ContentGallery::class);
    }

    public function status(){
        return $this->hasOne(ContentStatus::class);
    }

    public function tag(){
        return $this->belongsToMany(Tag::class, 'content_tags', 'content_id','tag_id');
    }
}
