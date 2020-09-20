<?php
/**
 * Created by PhpStorm.
 */

namespace WebAppId\Content\Repositories;


use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\TimeZone;
use WebAppId\Content\Repositories\Requests\TimeZoneRepositoryRequest;
use WebAppId\DDD\Tools\Lazy;
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