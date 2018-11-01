<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Models;

use Illuminate\Database\Eloquent\Model;

use App\Http\Models\User;

/**
 * Class Language
 * @package WebAppId\Content\Models
 */

class Language extends Model
{
    protected $table = 'languages';
    protected $fillable = ['id', 'code', 'name'];
    protected $hidden = ['user_id', 'created_at', 'updated_at'];

    public function content(){
        return $this->hasMany(Conent::class, 'language_id');
    }

    public function image(){
        return $this->hasOne(File::class, 'image_id');
    }

    public function user(){
        return $this->hasOne(User::class, 'user_id');
    }
}
