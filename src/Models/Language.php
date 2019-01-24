<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Models;

use App\Http\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Language
 * @package WebAppId\Content\Models
 */
class Language extends Model
{
    protected $table = 'languages';
    protected $fillable = ['id', 'code', 'name'];
    protected $hidden = ['user_id', 'created_at', 'updated_at'];
    
    public function contents()
    {
        return $this->hasMany(Conent::class, 'language_id');
    }
    
    public function image()
    {
        return $this->hasOne(File::class, 'image_id');
    }
    
    public function user()
    {
        return $this->hasOne(User::class, 'user_id');
    }
}
