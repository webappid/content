<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\QueryException;
use WebAppId\Content\Models\TimeZone;

/**
 * Class TimeZoneRepository
 * @package WebAppId\Content\Repositories
 */

class TimeZoneRepository
{

    /**
     * @param $data
     * @param TimeZone $timezone
     * @return bool|TimeZone
     */
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

    /**
     * @param $name
     * @param TimeZone $timezone
     * @return mixed
     */
    public function getTimeZoneByName($name, TimeZone $timezone)
    {
        return $timezone->where('name', $name)->get();
    }

    /**
     * @param $name
     * @param TimeZone $timezone
     * @return mixed
     */
    public function getOneTimeZoneByName($name, TimeZone $timezone)
    {
        return $timezone->where('name', '=', $name)->first();
    }

    /**
     * @param TimeZone $timeZone
     * @return mixed
     */
    public function getAllTimeZone(TimeZone $timeZone)
    {
        return $timeZone->get();
    }

    /**
     * @param $id
     * @param TimeZone $timezone
     * @return mixed
     */
    public function getTimeZoneById($id, TimeZone $timezone)
    {
        return $timezone->findOrFail($id);
    }
}