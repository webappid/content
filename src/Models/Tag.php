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
 * Class Tag
 * @package WebAppId\Content\Models
 */
class Tag extends Model
{

    use ModelTrait;

    protected $table = 'tags';

    protected $hidden = ['user_id', 'created_at', 'updated_at'];

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

    public function contents()
    {
        return $this->belongsToMany(Content::class, 'content_tags', 'tag_id', 'content_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'user_id');
    }
}
