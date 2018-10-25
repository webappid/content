<?php

namespace WebAppId\Content\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class TimeZone extends Model
{
    protected $table = 'time_zones';
    protected $fillable = [
        'code', 'name', 'minute',
    ];
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function content(){
        return $this->hasMany(Content::class, 'time_zone_id');
    }

    public function user(){
        return $this->hasOne(User::class, 'user_id');
    }
}
