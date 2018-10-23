<?php

namespace WebAppId\Content\Models;

use WebAppId\Content\Models\Content;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class ContentStatus extends Model
{
    protected $table = 'content_statuses';
    protected $fillable = ['id', 'name'];
    protected $hidden = ['user_id', 'created_at', 'updated_at'];

    public function content(){
        return $this->hasMany(Content::class, 'status_id');
    }
}
