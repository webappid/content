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
 * Class TimeZone
 * @package WebAppId\Content\Models
 */
class TimeZone extends Model
{

    use ModelTrait;

    protected $table = 'time_zones';
    protected $fillable = [
        'code', 'name', 'minute',
    ];
    protected $hidden = [
        'created_at', 'updated_at',
    ];

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
        return $this->hasMany(Content::class, 'time_zone_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'user_id');
    }
}
