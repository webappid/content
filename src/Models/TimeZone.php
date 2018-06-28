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

    public function addTimeZone($data)
    {
        try {
            $result          = new self();
            $result->code    = $data->code;
            $result->name    = $data->name;
            $result->minute  = $data->minute;
            $result->user_id = $data->user_id;
            $result->save();

            return $result;
        } catch (QueryException $e) {
            report($e);
            return false;
        }
    }

    public function getTimeZoneByName($name)
    {
        return $this->where('name', $name)->get();
    }

    public function getOneTimeZoneByName($name)
    {
        return $this->where('name', '=', $name)->firstOrFail();
    }

    public function getAllTimeZone()
    {
        return $this->get();
    }

    public function getTimeZoneById($id)
    {
        return $this->findOrFail($id);
    }
}
