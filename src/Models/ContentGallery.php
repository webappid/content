<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Models;

use Illuminate\Database\Eloquent\Model;
use WebAppId\Lazy\Traits\ModelTrait;

/**
 * Class ContentGallery
 * @package WebAppId\Content\Models
 */
class ContentGallery extends Model
{
    use ModelTrait;

    protected $table = 'content_galleries';

    protected $fillable = ['id', 'content_id', 'file_id', 'description'];

    protected $hidden = ['user_id', 'crated_at', 'updated_at'];

    public function content()
    {
        return $this->belongsTo(Content::class);
    }

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
