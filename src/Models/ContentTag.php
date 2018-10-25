<?php

namespace WebAppId\Content\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class ContentTag extends Model
{
    protected $table='content_tags';

    protected $hidden=['user_id','created_at','updated_at'];

    protected $fillable=['id','content_id','tag_id'];
}