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
 * Class Language
 * @package WebAppId\Content\Models
 */
class Language extends Model
{
    use ModelTrait;

    protected $table = 'languages';
    protected $fillable = ['id', 'code', 'name'];
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
        return $this->hasMany(Content::class, 'language_id');
    }

    public function image()
    {
        return $this->hasOne(File::class, 'image_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'user_id');
    }
}
