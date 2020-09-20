<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Models;

use Illuminate\Database\Eloquent\Model;
use WebAppId\Lazy\Traits\ModelTrait;

/**
 * Class ContentChild
 * @package WebAppId\Content\Models
 */
class ContentChild extends Model
{
    use ModelTrait;

    protected $table = 'content_children';

    protected $hidden = ['user_id', 'created_at', 'updated_at'];

    protected $fillable = ['id', 'content_parent_id', 'content_child_id'];

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

}
