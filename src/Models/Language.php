<?php

namespace WebAppId\Content\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class Language extends Model
{
    protected $table = 'languages';
    protected $fillable = ['id', 'code', 'name'];
    protected $hidden = ['user_id', 'created_at', 'updated_at'];
}
