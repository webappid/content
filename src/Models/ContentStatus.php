<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ContentStatus
 * @package WebAppId\Content\Models
 */
class ContentStatus extends Model
{
    protected $table = 'content_statuses';
    protected $fillable = ['id', 'name'];
    protected $hidden = ['user_id', 'created_at', 'updated_at'];
    
    public function content()
    {
        return $this->hasMany(Content::class, 'status_id');
    }
}
