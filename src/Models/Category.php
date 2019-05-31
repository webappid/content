<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Models;

use App\Http\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 2019-05-31
 * Time: 13:53
 * Class Category
 * @package WebAppId\Content\Models
 */
class Category extends Model
{
    protected $table = 'categories';
    
    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = ['id', 'code', 'name', 'user_id', 'parent_id'];
    
    public function contents()
    {
        return $this->belongsToMany(ContentCategory::class, 'content_categories', 'id', 'categories_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function status()
    {
        return $this->hasOne(CategoryStatus::class, 'status_id', 'id');
    }
}
