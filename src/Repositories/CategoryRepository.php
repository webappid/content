<?php

/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use WebAppId\Content\Models\Category;
use WebAppId\Content\Repositories\Contracts\CategoryRepositoryContract;
use WebAppId\Content\Repositories\Requests\CategoryRepositoryRequest;
use WebAppId\DDD\Tools\Lazy;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 22/04/20
 * Time: 00.15
 * Class CategoryRepository
 * @package WebAppId\Content\Repositories
 */
class CategoryRepository implements CategoryRepositoryContract
{
    /**
     * @inheritDoc
     */
    public function store(CategoryRepositoryRequest $categoryRepositoryRequest, Category $category): ?Category
    {
        try {
            $category = Lazy::copy($categoryRepositoryRequest, $category);
            $category->save();
            if ($category->parent != null) {
                $this->cleanCache($category->parent);
            }
            return $category;
        } catch (QueryException $queryException) {
            report($queryException);
            return null;
        }
    }

    protected function getColumn($category)
    {
        return $category
            ->select
            (
                'categories.id',
                'categories.code',
                'categories.name',
                'categories.user_id',
                'categories.status_id',
                'categories.parent_id',
                'category_statuses.id AS status_id',
                'category_statuses.name AS status_name',
                'users.name AS user_name',
                'users.email AS user_email'
            )
            ->join('category_statuses as category_statuses', 'categories.status_id', 'category_statuses.id')
            ->join('users as users', 'categories.user_id', 'users.id');
    }

    /**
     * @inheritDoc
     */
    public function update(int $id, CategoryRepositoryRequest $categoryRepositoryRequest, Category $category): ?Category
    {
        $category = $this->getById($id, $category);
        if ($category != null) {
            try {
                $category = Lazy::copy($categoryRepositoryRequest, $category);
                if ($category->parent != null) {
                    $this->cleanCache($category->parent);
                }
                $category->save();
                return $category;
            } catch (QueryException $queryException) {
                report($queryException);
            }
        }
        return $category;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id, Category $category): ?Category
    {
        return $this->getColumn($category)->find($id);
    }

    /**
     * @inheritDoc
     */
    public function getByCode(string $code, Category $category): ?Category
    {
        return $this->getColumn($category)->where('categories.code', $code)->first();
    }

    /**
     * @inheritDoc
     */
    public function getByName(string $name, Category $category): ?Category
    {
        return $this->getColumn($category)->where('categories.name', $name)->first();
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id, Category $category): bool
    {
        $category = $this->getById($id, $category);
        if ($category != null) {
            if ($category->parent != null) {
                $this->cleanCache($category->parent);
            }
            return $category->delete();
        } else {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function get(Category $category, int $length = 12, string $q = null): LengthAwarePaginator
    {
        return $this->getColumn($category)
            ->orderBy('categories.name', 'asc')
            ->when($q != null, function ($query) use ($q) {
                return $query->where('categories.code', 'LIKE', '%' . $q . '%');
            })
            ->paginate($length);
    }

    /**
     * @inheritDoc
     */
    public function getCount(Category $category, string $q = null): int
    {
        return $category
            ->when($q != null, function ($query) use ($q) {
                return $query->where('categories.code', 'LIKE', '%' . $q . '%');
            })
            ->count();
    }

    /**
     * @param Category $category
     */
    private function cleanCache(Category $category): void
    {
        Cache::forget('category-' . $category['code']);
    }

}
