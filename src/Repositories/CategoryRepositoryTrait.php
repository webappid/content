<?php
/**
 * Created by PhpStorm.
 */

namespace WebAppId\Content\Repositories;


use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use WebAppId\Content\Models\Category;
use WebAppId\Content\Repositories\Requests\CategoryRepositoryRequest;
use WebAppId\Lazy\Tools\Lazy;
use WebAppId\Lazy\Traits\RepositoryTrait;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 20/09/2020
 * Time: 18.12
 * Class CategoryRepositoryTrait
 * @package WebAppId\Content\Repositories
 */
trait CategoryRepositoryTrait
{
    use RepositoryTrait;

    /**
     * @param CategoryRepositoryRequest $categoryRepositoryRequest
     * @param Category $category
     * @return Category|null
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

    /**
     * @param Category $category
     */
    private function cleanCache(Category $category): void
    {
        Cache::forget('category-' . $category['code']);
    }

    /**
     * @param int $id
     * @param CategoryRepositoryRequest $categoryRepositoryRequest
     * @param Category $category
     * @return Category|null
     */
    public function update(int $id, CategoryRepositoryRequest $categoryRepositoryRequest, Category $category): ?Category
    {
        $category = $category->find($id);
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
     * @param int $id
     * @param Category $category
     * @return Category|null
     */
    public function getById(int $id, Category $category): ?Category
    {
        return $this
            ->getJoin($category)
            ->find($id, $this->getColumn());
    }

    /**
     * @param string $code
     * @param Category $category
     * @return Category|null
     */
    public function getByCode(string $code, Category $category): ?Category
    {
        return $this->getJoin($category)
            ->where('categories.code', $code)
            ->first($this->getColumn());
    }

    /**
     * @param string $name
     * @param Category $category
     * @return Category|null
     */
    public function getByName(string $name, Category $category): ?Category
    {
        return $this
            ->getJoin($category)
            ->where('categories.name', $name)
            ->first($this->getColumn());
    }

    /**
     * @param array $names
     * @param Category $category
     * @param $length
     * @return LengthAwarePaginator|null
     */
    public function getWhereInName(array $names, Category $category, $length = 12): ?LengthAwarePaginator
    {
        return $this
            ->getJoin($category)
            ->whereIn('categories.name', $names)
            ->paginate($length, $this->getColumn());
    }

    /**
     * @param int $id
     * @param Category $category
     * @return bool
     */
    public function delete(int $id, Category $category): bool
    {
        $category = $category->find($id);
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
     * @param Category $category
     * @param int $length
     * @param string|null $q
     * @return LengthAwarePaginator
     */
    public function get(Category $category, int $length = 12, string $q = null): LengthAwarePaginator
    {
        return $this
            ->getJoin($category)
            ->orderBy('categories.name', 'asc')
            ->when($q != null, function ($query) use ($q) {
                return $query->where('categories.code', 'LIKE', '%' . $q . '%');
            })
            ->paginate($length, $this->getColumn());
    }

    /**
     * @param Category $category
     * @param string|null $q
     * @return int
     */
    public function getCount(Category $category, string $q = null): int
    {
        return $category
            ->when($q != null, function ($query) use ($q) {
                return $query->where('categories.code', 'LIKE', '%' . $q . '%');
            })
            ->count();
    }

}