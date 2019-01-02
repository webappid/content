<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ContentGallery
 * @package WebAppId\Content\Models
 */
class ContentGallery extends Model
{
    protected $table = 'content_galleries';
    
    protected $fillable = ['id', 'content_id', 'file_id', 'description'];
    
    protected $hidden = ['user_id', 'crated_at', 'updated_at'];
    
    public function content()
    {
        return $this->belongsTo(Content::class);
    }
}
