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

    public function getContentByCode($code)
    {
        return $this->where('code', $code)->first();
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
