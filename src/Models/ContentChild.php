<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ContentChild
 * @package WebAppId\Content\Models
 */
class ContentChild extends Model
{
    protected $table='content_childs';

    protected $hidden=['user_id','created_at','updated_at'];

    protected $fillable=['id','content_parent_id','content_child_id'];
}