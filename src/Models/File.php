<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Models;

use Illuminate\Database\Eloquent\Model;
use WebAppId\User\Models\User;

/**
 * Class File
 * @package WebAppId\Content\Models
 */

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
