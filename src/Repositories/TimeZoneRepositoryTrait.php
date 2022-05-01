<?php
/**
 * Created by PhpStorm.
 */

namespace WebAppId\Content\Repositories;


use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\TimeZone;
use WebAppId\Content\Repositories\Requests\TimeZoneRepositoryRequest;
use WebAppId\Lazy\Tools\Lazy;
use WebAppId\Lazy\Traits\RepositoryTrait;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 20/09/2020
 * Time: 22.24
 * Class TimeZoneRepositoryTrait
 * @package WebAppId\Content\Repositories
 */
trait TimeZoneRepositoryTrait
{
    use RepositoryTrait;

    /**
     * @param TimeZoneRepositoryRequest $timeZoneRepositoryRequest
     * @param TimeZone $timeZone
     * @return TimeZone|null
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
     * @param int $id
     * @param TimeZoneRepositoryRequest $timeZoneRepositoryRequest
     * @param TimeZone $timeZone
     * @return TimeZone|null
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
     * @param int $id
     * @param TimeZone $timeZone
     * @return TimeZone|null
     */
    public function getById(int $id, TimeZone $timeZone): ?TimeZone
    {
        return $this->getJoin($timeZone)->find($id, $this->getColumn());
    }

    /**
     * @param int $id
     * @param TimeZone $timeZone
     * @return bool
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
     * @param TimeZone $timeZone
     * @param int $length
     * @param string|null $q
     * @return LengthAwarePaginator
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
     * @param TimeZone $timeZone
     * @param string|null $q
     * @return int
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
     * @param string $name
     * @param TimeZone $timezone
     * @return TimeZone|null
     */
    public function getByName(string $name, TimeZone $timezone): ?TimeZone
    {
        return $this
            ->getJoin($timezone)
            ->where('time_zones.name', $name)->first($this->getColumn());
    }
}