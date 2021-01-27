<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use WebAppId\Lazy\Traits\ModelTrait;
use WebAppId\User\Models\User;

/**
 * Class File
 * @package WebAppId\Content\Models
 */
class File extends Model
{

    use ModelTrait;

    protected $table = 'files';

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = ['id', 'name', 'description', 'alt', 'mime_type_id', 'owner_id', 'user_id'];

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

        $columns['file_uri'] = DB::raw('CONCAT(REPLACE("' . route('file.ori', 'file_name') . '", "file_name" , files.name),"/") AS file_uri');

        return $columns;
    }

    public function mime()
    {
        return $this->belongsTo(MimeType::class, 'mime_type_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
