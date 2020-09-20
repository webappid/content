<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Models;

use Illuminate\Database\Eloquent\Model;
use WebAppId\Lazy\Traits\ModelTrait;

/**
 * Class ContentStatus
 * @package WebAppId\Content\Models
 */
class ContentStatus extends Model
{
    use ModelTrait;

    protected $table = 'content_statuses';
    protected $fillable = ['id', 'name'];
    protected $hidden = ['user_id', 'created_at', 'updated_at'];

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
        return $this->hasMany(Content::class, 'status_id');
    }

}
