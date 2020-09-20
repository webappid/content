<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Models;

use Illuminate\Database\Eloquent\Model;
use WebAppId\Lazy\Traits\ModelTrait;
use WebAppId\User\Models\User;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 2019-05-31
 * Time: 13:53
 * Class Category
 * @package WebAppId\Content\Models
 */
class Category extends Model
{
    use ModelTrait;

    protected $table = 'categories';

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = ['id', 'code', 'name', 'user_id', 'parent_id'];

    public function getColumns(bool $isFresh = false)
    {
        $columns = $this->getAllColumn($isFresh);

        $forbiddenField = [
            "created_at",
            "updated_at"
        ];
        foreach ($forbiddenField as $item) {
            unset($columns[$item]);
        }

        return $columns;
    }

    public function contents()
    {
        return $this->belongsToMany(ContentCategory::class, 'content_categories', 'id', 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function status()
    {
        return $this->hasOne(CategoryStatus::class, 'status_id', 'id');
    }

    public function childs()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
}
