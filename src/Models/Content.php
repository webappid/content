<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Models;

use Illuminate\Database\Eloquent\Model;
use WebAppId\User\Models\User;

/**
 * Class Content
 * @package WebAppId\Content\Models
 */
class Content extends Model
{
    //
    protected $table = 'contents';

    protected $hidden = ['created_at', 'updated_at', 'owner_id', 'user_id'];

    protected $fillable = ['id', 'code', 'title', 'description', 'keyword', 'og_title', 'og_description', 'default_image', ' status_id', 'language_id', 'publish_date', 'additional_info', 'content'];

    public function childs()
    {
        return $this->belongsToMany(Content::class, 'content_childs', 'content_parent_id', 'content_child_id');
    }

    public function parents()
    {
        return $this->belongsToMany(Content::class, 'content_childs', 'content_child_id', 'content_parent_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'content_categories', 'content_id', 'categories_id');
    }

    public function galleries()
    {
        return $this->belongsToMany(File::class, 'content_galleries', 'content_id', 'file_id');
    }

    public function status()
    {
        return $this->hasOne(ContentStatus::class, 'status_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'content_tags', 'content_id', 'tag_id');
    }

    public function file()
    {
        return $this->hasOne(File::class, 'id', 'default_image');
    }

    public function language()
    {
        return $this->hasOne(Language::class, 'language_id');
    }

    public function timezone()
    {
        return $this->hasOne(TimeZone::class, 'time_zone_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
