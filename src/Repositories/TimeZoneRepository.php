<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\QueryException;
use WebAppId\Content\Models\TimeZone;
use WebAppId\Content\Services\Params\AddTimeZoneParam;

/**
 * Class TimeZoneRepository
 * @package WebAppId\Content\Repositories
 */
class TimeZoneRepository
{
    
    /**
     * @param AddTimeZoneParam $addTimeZoneParam
     * @param TimeZone $timezone
     * @return TimeZone|null
     */
    public function addTimeZone(AddTimeZoneParam $addTimeZoneParam, TimeZone $timezone): ?TimeZone
    {
        try {
            $timezone->code = $addTimeZoneParam->getCode();
            $timezone->name = $addTimeZoneParam->getName();
            $timezone->minute = $addTimeZoneParam->getMinute();
            $timezone->user_id = $addTimeZoneParam->getUserId();
            $timezone->save();
    
            return $timezone;
        } catch (QueryException $e) {
            report($e);
            return null;
        }
    }
    
    /**
     * @param $name
     * @param TimeZone $timezone
     * @return object|null
     */
    public function getTimeZoneByName(string $name, TimeZone $timezone): ?object
    {
        return $timezone->where('name', $name)->get();
    }
    
    /**
     * @param $name
     * @param TimeZone $timezone
     * @return TimeZone|null
     */
    public function getOneTimeZoneByName(string $name, TimeZone $timezone): ?TimeZone
    {
        return $timezone->where('name', '=', $name)->first();
    }
    
    /**
     * @param TimeZone $timeZone
     * @return
     */
    public function getAllTimeZone(TimeZone $timeZone): ?object
    {
        return $timeZone->get();
    }
    
    /**
     * @param $id
     * @param TimeZone $timezone
     * @return TimeZone|null
     */
    public function getTimeZoneById(int $id, TimeZone $timezone): ?TimeZone
    {
        return $timezone->findOrFail($id);
    }
}