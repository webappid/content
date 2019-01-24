<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Models;

use App\Http\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TimeZone
 * @package WebAppId\Content\Models
 */
class TimeZone extends Model
{
    protected $table = 'time_zones';
    protected $fillable = [
        'code', 'name', 'minute',
    ];
    protected $hidden = [
        'created_at', 'updated_at',
    ];
    
    public function contents()
    {
        return $this->hasMany(Content::class, 'time_zone_id');
    }
    
    public function user()
    {
        return $this->hasOne(User::class, 'user_id');
    }
}
