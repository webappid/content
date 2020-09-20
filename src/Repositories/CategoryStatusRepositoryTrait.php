<?php
/**
 * Created by PhpStorm.
 */

namespace WebAppId\Content\Repositories;


use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\CategoryStatus;
use WebAppId\Content\Repositories\Requests\CategoryStatusRepositoryRequest;
use WebAppId\Lazy\Tools\Lazy;
use WebAppId\Lazy\Traits\RepositoryTrait;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 20/09/2020
 * Time: 19.51
 * Class CategoryStatusRepositoryTrait
 * @package WebAppId\Content\Repositories
 */
trait CategoryStatusRepositoryTrait
{
    use RepositoryTrait;

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
     * @inheritDoc
     */
    public function update(int $id, CategoryStatusRepositoryRequest $categoryStatusRepositoryRequest, CategoryStatus $categoryStatus): ?CategoryStatus
    {
        $categoryStatus = $categoryStatus->find($id);
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
        return $categoryStatus->find($id);
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id, CategoryStatus $categoryStatus): bool
    {
        $categoryStatus = $categoryStatus->find($id);
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
        return $this->getJoin($categoryStatus)
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
        return $this
            ->getJoin($categoryStatus)
            ->where('name', $name)
            ->first($this->getColumn());
    }
}