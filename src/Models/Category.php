<?php

namespace WebAppId\Content\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class Category extends Model
{
    protected $table='categories';

    protected $hidden=['created_at','updated_at'];

    protected $fillable=['id','code','name'];

    public function content(){
        return $this->belongsToMany(ContentCategory::class, 'content_categories', 'id','categories_id');
    }
}
