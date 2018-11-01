<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ContentTag
 * @package WebAppId\Content\Models
 */

class ContentTag extends Model
{
    protected $table='content_tags';

    protected $hidden=['user_id','created_at','updated_at'];

    protected $fillable=['id','content_id','tag_id'];
}