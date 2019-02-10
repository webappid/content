<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\QueryException;
use WebAppId\Content\Models\Category;

/**
 * Class CategoryRepository
 * @package WebAppId\Content\Repositories
 */
class CategoryRepository
{
    /**
     * Method To Add Data Category
     *
     * @param Object $data
     * @param Category $category
     * @return Category|null
     */
    public function addCategory($data, Category $category): ?Category
    {
        try {
            $category->code = $data->code;
            $category->name = $data->name;
            if (isset($data->parent_id)) {
                $category->parent_id = $data->parent_id;
            } else {
                $category->parent_id = null;
            }
            $category->status_id = $data->status_id;
            $category->user_id = $data->user_id;
            
            $category->save();
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
    public function getOne($id, Category $category): ?Category
    {
        return $category->findOrFail($id);
    }
    
    /**
     * Method To Update Category
     *
     * @param $request
     * @param Integer $id
     * @param Category $category
     * @return Category|null
     */
    public function updateCategory($request, $id, Category $category): ?Category
    {
        try {
            $categoryData = $this->getOne($id, $category);
            
            if (!empty($categoryData)) {
                $categoryData->code = $request->code;
                $categoryData->name = $request->name;
                if (isset($request->parent_id)) {
                    $category->parent_id = $request->parent_id;
                } else {
                    $category->parent_id = null;
                }
                $categoryData->status_id = $request->status_id;
                $categoryData->user_id = $request->user_id;
                $categoryData->save();
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
    
    public function deleteCategory($id, Category $category): bool
    {
        try {
            $categoryData = $this->getOne($id, $category);
            if (!empty($categoryData)) {
                $categoryData->delete();
                return true;
            } else {
                return false;
            }
        } catch (QueryException $e) {
            report($e);
            return false;
        }
    }
    
    /**
     * Get All Category
     *
     * @param Category $category
     * @return Category|null
     */
    public function getAll(Category $category): ?object
    {
        return $category->all();
    }
    
    /**
     * Method For Get Data Category Use name like or code
     *
     * @param Category $category
     * @param String $search
     * @return Category|null list category / empty object
     */
    public function getDataWhere(Category $category, $search = ""): ?object
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
     * @return mixed
     */
    public function getDatable(Category $category,
                               $search,
                               $order_column,
                               $order_dir,
                               $limit_start,
                               $limit_length): ?object
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
     * @param $search
     * @param $category
     * @return mixed
     */
    private function getQueryCategory($search, $category)
    {
        return $category->where('code', 'LIKE', '%' . $search . '%')
            ->orWhere('name', 'LIKE', '%' . $search . '%');
    }
    
    
    /**
     * @param string $search
     * @param Category $category
     * @return Category|null
     */
    public function getSearch(Category $category, $search = ""): ?object
    {
        return $this->getQueryCategory($search, $category)->get();
    }
    
    /**
     * @param string $search
     * @param Category $category
     * @return mixed
     */
    public function getSearchOne(Category $category, $search = ""): ?Category
    {
        return $this->getQueryCategory($search, $category)->first();
    }
    
    /**
     * @param Category $category
     * @return mixed
     */
    public function getAllCount(Category $category): int
    {
        return $category->count();
    }
    
    /**
     * @param string $search
     * @param Category $category
     * @return mixed
     */
    public function getSearchCount(Category $category, $search = ""): int
    {
        return $this->getQueryCategory($search, $category)->count();
    }
    
    /**
     * @param $code
     * @param Category $category
     * @return mixed
     */
    public function getCategoryByCode($code, Category $category): ?Category
    {
        return $category->where('code', $code)->first();
    }
    
}
