<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories;


use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\CategoryStatus;
use WebAppId\Content\Repositories\Requests\CategoryStatusRepositoryRequest;
use WebAppId\Content\Services\Params\AddCategoryStatusParam;
use WebAppId\DDD\Tools\Lazy;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 22/04/20
 * Time: 00.15
 * Class CategoryStatusRepository
 * @package WebAppId\Content\Repositories
 */
class CategoryStatusRepository
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

    protected function getColumn($content)
    {
        return $content
            ->select
            (
                'category_statuses.id',
                'category_statuses.name'
            );
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
        return $this->getColumn($categoryStatus)->find($id);
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
    public function get(CategoryStatus $categoryStatus, int $length = 12): LengthAwarePaginator
    {
        return $this->getColumn($categoryStatus)->paginate($length);
    }

    /**
     * @inheritDoc
     */
    public function getCount(CategoryStatus $categoryStatus): int
    {
        return $categoryStatus->count();
    }

    private function getQueryWhere(string $q, CategoryStatus $categoryStatus)
    {
        return $this->getColumn($categoryStatus)
            ->where('name', 'LIKE', '%' . $q . '%');
    }

    /**
     * @inheritDoc
     */
    public function getWhere(string $q, CategoryStatus $categoryStatus, int $length = 12): LengthAwarePaginator
    {
        return $this
            ->getQueryWhere($q, $categoryStatus)
            ->paginate($length);
    }

    /**
     * @inheritDoc
     */
    public function getWhereCount(string $q, CategoryStatus $categoryStatus, int $length = 12): int
    {
        return $this
            ->getQueryWhere($q, $categoryStatus)
            ->count();
    }

    /**
     * @param AddCategoryStatusParam $addCategoryStatusParam
     * @param CategoryStatus $categoryStatus
     * @return null|CategoryStatus
     */
    public function addCategoryStatus(AddCategoryStatusParam $addCategoryStatusParam, CategoryStatus $categoryStatus): ?CategoryStatus
    {
        try {
            $categoryStatus->name = $addCategoryStatusParam->getName();
            $categoryStatus->save();
            return $categoryStatus;
        } catch (QueryException $queryException) {
            report($queryException);
            return null;
        }
    }

    /**
     * @param string $name
     * @param CategoryStatus $categoryStatus
     * @return CategoryStatus|null
     */
    public function getByName(string $name, CategoryStatus $categoryStatus): ?CategoryStatus
    {
        return $categoryStatus->where('name', $name)->first();
    }
}
