<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 04/11/18
 * Time: 03.35
 */

namespace WebAppId\Content\Models;


use Illuminate\Database\Eloquent\Model;

class CategoryStatus extends Model
{
    protected $table = 'category_statuses';
    
    protected $hidden = ['created_at', 'updated_at'];
    
    protected $fillable = ['id', 'name'];
    
    public function category()
    {
        return $this->hasMany(Category::class, 'status_id', 'id');
    }
}