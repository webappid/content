<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 04/11/18
 * Time: 03.35
 */

namespace WebAppId\Content\Models;


use Illuminate\Database\Eloquent\Model;
use WebAppId\Lazy\Traits\ModelTrait;

class CategoryStatus extends Model
{
    use ModelTrait;

    protected $table = 'category_statuses';

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = ['id', 'name'];

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

    public function categories()
    {
        return $this->hasMany(Category::class, 'status_id', 'id');
    }
}