<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\CategoryStatus;
use WebAppId\Content\Repositories\Contracts\CategoryStatusRepositoryContract;
use WebAppId\Content\Repositories\Requests\CategoryStatusRepositoryRequest;
use WebAppId\DDD\Tools\Lazy;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 22/04/20
 * Time: 00.15
 * Class CategoryStatusRepository
 * @package WebAppId\Content\Repositories
 */
class CategoryStatusRepository implements CategoryStatusRepositoryContract
{
    /**
     * @inheritDoc
     */
    public function store(CategoryStatusRepositoryRequest $categoryStatusRepositoryRequest, CategoryStatus $categoryStatus): ?CategoryStatus
    {
        try {
            $categoryStatus = Lazy::copy($categoryStatusRepositoryRequest, $categoryStatus);
            $categoryStatus->save();
            return $categoryStatus;
        } catch (QueryException $queryException) {
            report($queryException);
            return null;
        }
    }

    /**
     * @param $categoryStatus
     * @return Builder
     */
    protected function getJoin($categoryStatus): Builder
    {
        return $categoryStatus;
    }

    /**
     * @return array|string[]
     */
    protected function getColumn(): array
    {
        return [
            'category_statuses.id',
            'category_statuses.name'
        ];
    }

    /**
     * @inheritDoc
     */
    public function update(int $id, CategoryStatusRepositoryRequest $categoryStatusRepositoryRequest, CategoryStatus $categoryStatus): ?CategoryStatus
    {
        $categoryStatus = $this->getById($id, $categoryStatus);
        if ($categoryStatus != null) {
            try {
                $categoryStatus = Lazy::copy($categoryStatusRepositoryRequest, $categoryStatus);
                $categoryStatus->save();
                return $categoryStatus;
            } catch (QueryException $queryException) {
                report($queryException);
            }
        }
        return $categoryStatus;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id, CategoryStatus $categoryStatus): ?CategoryStatus
    {
        return $this->getJoin($categoryStatus)->find($id, $this->getColumn());
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id, CategoryStatus $categoryStatus): bool
    {
        $categoryStatus = $this->getById($id, $categoryStatus);
        if ($categoryStatus != null) {
            return $categoryStatus->delete();
        } else {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function get(CategoryStatus $categoryStatus, int $length = 12, string $q = null): LengthAwarePaginator
    {
        return $this
            ->getJoin($categoryStatus)
            ->when($q != null, function ($query) use ($q) {
                return $query->where('category_statuses.name', 'LIKE', '%' . $q . '%');
            })
            ->orderBy('category_statuses.name', 'asc')
            ->paginate($length, $this->getColumn());
    }

    /**
     * @inheritDoc
     */
    public function getCount(CategoryStatus $categoryStatus, string $q = null): int
    {
        return $categoryStatus
            ->when($q != null, function ($query) use ($q) {
                return $query->where('category_statuses.name', 'LIKE', '%' . $q . '%');
            })
            ->count();
    }

    /**
     * @inheritDoc
     */
    public function getByName(string $name, CategoryStatus $categoryStatus): ?CategoryStatus
    {
        return $categoryStatus->where('name', $name)->first($this->getColumn());
    }
}
