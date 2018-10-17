<?php

namespace WebAppId\Content\Models;

use WebAppId\Content\Models\Content;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class Tag extends Model
{
    protected $table='tags';

    protected $hidden=['user_id','created_at','updated_at'];

    protected $fillable=['id','name'];

    public function content(){
        return $this->belongsToMany(Content::class, 'content_tags', 'tag_id','content_id');
    }
}