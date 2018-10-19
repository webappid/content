<?php

namespace WebAppId\Content\Repositories;

use WebAppId\Content\Models\TimeZone;

class TimeZoneRepository
{

    public function addTimeZone($data, TimeZone $timezone)
    {
        try {
            $timezone->code    = $data->code;
            $timezone->name    = $data->name;
            $timezone->minute  = $data->minute;
            $timezone->user_id = $data->user_id;
            $timezone->save();

            return $timezone;
        } catch (QueryException $e) {
            report($e);
            return false;
        }
    }

    public function getTimeZoneByName($name, TimeZone $timezone)
    {
        return $timezone->where('name', $name)->get();
    }

    public function getOneTimeZoneByName($name, TimeZone $timezone)
    {
        return $timezone->where('name', '=', $name)->first();
    }

    public function getAllTimeZone()
    {
        return $timezone->get();
    }

    public function getTimeZoneById($id, TimeZone $timezone)
    {
        return $timezone->findOrFail($id);
    }
}