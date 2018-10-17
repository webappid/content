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
