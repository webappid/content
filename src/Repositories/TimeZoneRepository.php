<?php

/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\TimeZone;
use WebAppId\Content\Repositories\Contracts\TimeZoneRepositoryContract;
use WebAppId\Content\Repositories\Requests\TimeZoneRepositoryRequest;
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

    protected function getColumn($timeZone)
    {
        return $timeZone
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
    public function get(TimeZone $timeZone, int $length = 12, string $q = null): LengthAwarePaginator
    {
        return $this
            ->getColumn($timeZone)
            ->when($q != null, function ($query) use ($q) {
                return $query->where('time_zones.name', 'LIKE', '%' . $q . '%');
            })
            ->orderBy('time_zones.name', 'asc')
            ->paginate($length);
    }

    /**
     * @inheritDoc
     */
    public function getCount(TimeZone $timeZone, string $q = null): int
    {
        return $timeZone
            ->when($q != null, function ($query) use ($q) {
                return $query->where('time_zones.name', 'LIKE', '%' . $q . '%');
            })
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
}
