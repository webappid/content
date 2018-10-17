<?php

namespace WebAppId\Content\Models;

use WebAppId\Content\Models\File;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class MimeType extends Model
{
    protected $table = 'mime_types';
    protected $fillable = ['id', 'name'];
    protected $hidden = ['user_id', 'created_at', 'updated_at'];

    public function file()
    {
        return $this->hasOne(File::class);
    }
}
