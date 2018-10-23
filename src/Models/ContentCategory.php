<?php

namespace WebAppId\Content\Models;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Model; 

class ContentCategory extends Model
{
    //
    protected $table = 'content_categories';

    protected $hidden = ['created_at','updated_at'];

    protected $fillable = ['id','content_id','categories_id'];
    
}
