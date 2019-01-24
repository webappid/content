<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Models;

use App\Http\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tag
 * @package WebAppId\Content\Models
 */
class Tag extends Model
{
    protected $table = 'tags';
    
    protected $hidden = ['user_id', 'created_at', 'updated_at'];
    
    protected $fillable = ['id', 'name'];
    
    public function contents()
    {
        return $this->belongsToMany(Content::class, 'content_tags', 'tag_id', 'content_id');
    }
    
    public function user()
    {
        return $this->hasOne(User::class, 'user_id');
    }
}