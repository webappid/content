<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use WebAppId\Content\Models\Category;
use WebAppId\Content\Services\Params\AddCategoryParam;

/**
 * Class CategoryRepository
 * @package WebAppId\Content\Repositories
 */
class CategoryRepository
{
    /**
     * Method To Add Data Category
     *
     * @param AddCategoryParam $addCategoryParam
     * @param Category $category
     * @return Category|null
     */
    public function addCategory(AddCategoryParam $addCategoryParam, Category $category): ?Category
    {
        try {
            $category->code = $addCategoryParam->getCode();
            $category->name = $addCategoryParam->getName();
            $category->parent_id = $addCategoryParam->getParentId();
            $category->status_id = $addCategoryParam->getStatusId();
            $category->user_id = $addCategoryParam->getUserId();
            $category->save();
            if ($addCategoryParam->getParentId() != null) {
                $this->cleanCache($category->parent);
            }
            return $category;
        } catch (QueryException $e) {
            report($e);
            return null;
        }
    }

    /**
     * Method To Get Data Category
     *
     * @param Integer $id
     * @param Category $category
     * @return Category|null
     */
    public function getOne(int $id, Category $category): ?Category
    {
        return $category->findOrFail($id);
    }

    /**
     * Method To Update Category
     *
     * @param AddCategoryParam $addCategoryParam
     * @param Integer $id
     * @param Category $category
     * @return Category|null
     */
    public function updateCategory(AddCategoryParam $addCategoryParam, int $id, Category $category): ?Category
    {
        try {
            $categoryData = $this->getOne($id, $category);

            if (!empty($categoryData)) {
                $categoryData->code = $addCategoryParam->getCode();
                $categoryData->name = $addCategoryParam->getName();
                $categoryData->parent_id = $addCategoryParam->getParentId();
                $categoryData->status_id = $addCategoryParam->getStatusId();
                $categoryData->user_id = $addCategoryParam->getUserId();
                $categoryData->save();
                if ($addCategoryParam->getParentId() != null) {
                    $this->cleanCache($categoryData->parent);
                }
                return $categoryData;
            } else {
                return null;
            }
        } catch (QueryException $e) {
            report($e);
            return null;
        }
    }

    /**
     * Method to Delete category Data
     *
     * @param Integer $id
     * @param Category $category
     * @return bool
     * @throws \Exception
     */

    public function deleteCategory(int $id, Category $category): bool
    {
        try {
            $category = $this->getOne($id, $category);
            if ($category->parent_id != null) {
                $this->cleanCache($category->parent);
            }
            return $category->delete();
        } catch (QueryException $e) {
            report($e);
            return false;
        }
    }

    /**
     * Get All Category
     *
     * @param Category $category
     * @return Collection
     */
    public function getAll(Category $category): Collection
    {
        return $category->all();
    }

    /**
     * Method For Get Data Category Use name like or code
     *
     * @param Category $category
     * @param String $search
     * @return Collection list category / empty object
     */
    public function getDataWhere(Category $category, $search = ""): Collection
    {
        return $category->where('code', $search)
            ->orWhere('name', 'LIKE', '%' . $search . '%')
            ->get();
    }


    /**
     * @param Category $category
     * @param $search
     * @param $order_column
     * @param $order_dir
     * @param $limit_start
     * @param $limit_length
     * @return Collection
     */
    public function getDatable(Category $category,
                               $search,
                               $order_column,
                               $order_dir,
                               $limit_start,
                               $limit_length): Collection
    {
        return $category
            ->select('id', 'code', 'name')
            ->where('code', 'LIKE', '%' . $search . '%')
            ->orWhere('name', 'LIKE', '%' . $search . '%')
            ->orderBy($order_column, $order_dir)
            ->offset($limit_start)
            ->limit($limit_length)
            ->get();
    }

    /**
     * @param string $search
     * @param Category $category
     * @return object
     */
    private function getQueryCategory(string $search, Category $category)
    {
        return $category->where('code', 'LIKE', '%' . $search . '%')
            ->orWhere('name', 'LIKE', '%' . $search . '%');
    }


    /**
     * @param Category $category
     * @param string $search
     * @return Collection
     */
    public function getSearch(Category $category, string $search = ""): Collection
    {
        return $this->getQueryCategory($search, $category)->get();
    }

    /**
     * @param Category $category
     * @param string $search
     * @return Category|null
     */
    public function getSearchOne(Category $category, string $search = ""): ?Category
    {
        return $this->getQueryCategory($search, $category)->first();
    }

    /**
     * @param Category $category
     * @return int
     */
    public function getAllCount(Category $category): int
    {
        return $category->count();
    }

    /**
     * @param Category $category
     * @param string $search
     * @return int
     */
    public function getSearchCount(Category $category, string $search = ""): int
    {
        return $this->getQueryCategory($search, $category)->count();
    }

    /**
     * @param string $code
     * @param Category $category
     * @return Category|null
     */
    public function getCategoryByCode(string $code, Category $category): ?Category
    {
        return $category->where('code', $code)->first();
    }

    /**
     * @param Category $category
     */
    private function cleanCache(Category $category): void
    {
        if ($category->parent_id != null) {
            $this->cleanCache($category->parent);
        }
        Cache::forget('category-' . $category['code']);
    }

}
