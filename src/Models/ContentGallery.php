<?php

namespace WebAppId\Content\Models;

use WebAppId\Content\Models\Content;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Model; 

class ContentGallery extends Model
{
    protected $table='content_galleries';
    
    protected $fillable=['id','content_id','file_id','description'];

    protected $hidden = ['user_id','crated_at', 'updated_at'];

    public function content(){
        return $this->belongsTo(Content::class);
    }
}
