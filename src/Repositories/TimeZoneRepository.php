<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\TimeZone;
use WebAppId\Content\Repositories\Contracts\TimeZoneRepositoryContract;
use WebAppId\Content\Repositories\Requests\TimeZoneRepositoryRequest;
use WebAppId\Content\Services\Params\AddTimeZoneParam;
use WebAppId\DDD\Tools\Lazy;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 22/04/20
 * Time: 04.56
 * Class TimeZoneRepository
 * @package WebAppId\Content\Repositories
 */
class TimeZoneRepository implements TimeZoneRepositoryContract
{

    /**
     * @inheritDoc
     */
    public function store(TimeZoneRepositoryRequest $timeZoneRepositoryRequest, TimeZone $timeZone): ?TimeZone
    {
        try {
            $timeZone = Lazy::copy($timeZoneRepositoryRequest, $timeZone);
            $timeZone->save();
            return $timeZone;
        } catch (QueryException $queryException) {
            report($queryException);
            return null;
        }
    }

    protected function getColumn($content)
    {
        return $content
            ->select
            (
                'time_zones.id',
                'time_zones.code',
                'time_zones.name',
                'time_zones.minute',
                'users.id',
                'users.name'
            )
            ->join('users as users', 'time_zones.user_id', 'users.id');
    }

    /**
     * @inheritDoc
     */
    public function update(int $id, TimeZoneRepositoryRequest $timeZoneRepositoryRequest, TimeZone $timeZone): ?TimeZone
    {
        $timeZone = $this->getById($id, $timeZone);
        if ($timeZone != null) {
            try {
                $timeZone = Lazy::copy($timeZoneRepositoryRequest, $timeZone);
                $timeZone->save();
                return $timeZone;
            } catch (QueryException $queryException) {
                report($queryException);
            }
        }
        return $timeZone;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id, TimeZone $timeZone): ?TimeZone
    {
        return $this->getColumn($timeZone)->find($id);
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id, TimeZone $timeZone): bool
    {
        $timeZone = $this->getById($id, $timeZone);
        if ($timeZone != null) {
            return $timeZone->delete();
        } else {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function get(TimeZone $timeZone, int $length = 12): LengthAwarePaginator
    {
        return $this->getColumn($timeZone)->paginate($length);
    }

    /**
     * @inheritDoc
     */
    public function getCount(TimeZone $timeZone): int
    {
        return $timeZone->count();
    }

    private function getQueryWhere(string $q, TimeZone $timeZone)
    {
        return $this->getColumn($timeZone)
            ->where('code', 'LIKE', '%' . $q . '%');
    }

    /**
     * @inheritDoc
     */
    public function getWhere(string $q, TimeZone $timeZone, int $length = 12): LengthAwarePaginator
    {
        return $this
            ->getQueryWhere($q, $timeZone)
            ->paginate($length);
    }

    /**
     * @inheritDoc
     */
    public function getWhereCount(string $q, TimeZone $timeZone, int $length = 12): int
    {
        return $this
            ->getQueryWhere($q, $timeZone)
            ->count();
    }

    /**
     * @inheritDoc
     */
    public function getByName(string $name, TimeZone $timezone): ?TimeZone
    {
        return $this
            ->getColumn($timezone)
            ->where('time_zones.name', $name)->first();
    }

    /**
     * @param AddTimeZoneParam $addTimeZoneParam
     * @param TimeZone $timezone
     * @return TimeZone|null
     * @deprecated
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
     * @param string $name
     * @param TimeZone $timezone
     * @return Collection
     * @deprecated
     */
    public function getTimeZoneByName(string $name, TimeZone $timezone): Collection
    {
        return $timezone->where('name', $name)->get();
    }

    /**
     * @param TimeZone $timeZone
     * @return Collection
     * @deprecated
     */
    public function getAllTimeZone(TimeZone $timeZone): Collection
    {
        return $timeZone->get();
    }

    /**
     * @param $id
     * @param TimeZone $timezone
     * @return TimeZone|null
     * @deprecated
     */
    public function getTimeZoneById(int $id, TimeZone $timezone): ?TimeZone
    {
        return $timezone->findOrFail($id);
    }
}
