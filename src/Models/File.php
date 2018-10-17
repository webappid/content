<?php

namespace WebAppId\Content\Models;

use WebAppId\Content\Models\MimeType;
use WebAppId\Content\Models\Content;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class File extends Model
{
    protected $table='files';

    protected $hidden=['created_at','updated_at'];

    protected $fillable=['id','name','description','alt','mime_type_id', 'owner_id','user_id'];

    public function mime(){
        return $this->belongsTo(MimeType::class);
    }

    public function content(){
        return $this->belongsTo(Content::class);
    }

    public function getFileCount(){
        return $this->count();
    }
}
