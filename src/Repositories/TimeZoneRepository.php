<?php

namespace WebAppId\Content\Repositories;

use WebAppId\Content\Models\TimeZone;

class TimeZoneRepository
{
    private $timeZone;

    public function __construct(TimeZone $timeZone)
    {
        $this->timeZone = $timeZone;
    }

    public function addTimeZone($data)
    {
        try {
            $this->timeZone->code    = $data->code;
            $this->timeZone->name    = $data->name;
            $this->timeZone->minute  = $data->minute;
            $this->timeZone->user_id = $data->user_id;
            $this->timeZone->save();

            return $this->timeZone;
        } catch (QueryException $e) {
            report($e);
            return false;
        }
    }

    public function getTimeZoneByName($name)
    {
        return $this->timeZone->where('name', $name)->get();
    }

    public function getOneTimeZoneByName($name)
    {
        return $this->timeZone->where('name', '=', $name)->first();
    }

    public function getAllTimeZone()
    {
        return $this->timeZone->get();
    }

    public function getTimeZoneById($id)
    {
        return $this->timeZone->findOrFail($id);
    }
}