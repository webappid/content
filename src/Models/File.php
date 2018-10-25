<?php

namespace WebAppId\Content\Models;

use WebAppId\Content\Models\MimeType;
use WebAppId\Content\Models\Content;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

use App\Http\Model\User;

class File extends Model
{
    protected $table='files';

    protected $hidden=['created_at','updated_at'];

    protected $fillable=['id','name','description','alt','mime_type_id', 'owner_id','user_id'];

    public function mime(){
        return $this->belongsTo(MimeType::class,'mime_type_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function owner(){
        return $this->belongsTo(User::class, 'owner_id');
    }
}