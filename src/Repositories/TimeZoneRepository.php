<?php

/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\Eloquent\Builder;
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

    /**
     * @param TimeZone $timeZone
     * @return Builder
     */
    protected function getJoin(TimeZone $timeZone): Builder
    {
        return $timeZone
            ->join('users as users', 'time_zones.user_id', 'users.id');
    }

    /**
     * @return array|string[]
     */
    protected function getColumn(): array
    {
        return [
            'time_zones.id',
            'time_zones.code',
            'time_zones.name',
            'time_zones.minute',
            'users.id AS user_id',
            'users.name AS user_name'
        ];
    }

    /**
     * @inheritDoc
     */
    public function update(int $id, TimeZoneRepositoryRequest $timeZoneRepositoryRequest, TimeZone $timeZone): ?TimeZone
    {
        $timeZone = $timeZone->find($id);
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
        return $this->getJoin($timeZone)->find($id, $this->getColumn());
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id, TimeZone $timeZone): bool
    {
        $timeZone = $timeZone->find($id);
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
            ->getJoin($timeZone)
            ->when($q != null, function ($query) use ($q) {
                return $query->where('time_zones.name', 'LIKE', '%' . $q . '%');
            })
            ->orderBy('time_zones.name', 'asc')
            ->paginate($length, $this->getColumn());
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
            ->getJoin($timezone)
            ->where('time_zones.name', $name)->first($this->getColumn());
    }
}
