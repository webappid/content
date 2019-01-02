<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ContentCategory
 * @package WebAppId\Content\Models
 */
class ContentCategory extends Model
{
    //
    protected $table = 'content_categories';
    
    protected $hidden = ['created_at', 'updated_at'];
    
    protected $fillable = ['id', 'content_id', 'categories_id'];
    
}
