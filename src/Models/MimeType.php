<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MimeType
 * @package WebAppId\Content\Models
 */
class MimeType extends Model
{
    protected $table = 'mime_types';
    protected $fillable = ['id', 'name'];
    protected $hidden = ['user_id', 'created_at', 'updated_at'];
    
    public function files()
    {
        return $this->hasMany(File::class, 'mime_type_id');
    }
}
