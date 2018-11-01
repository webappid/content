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
     * @return Category $category
     */
    public function addCategory($data, Category $category)
    {
        try {
            $category->code = $data->code;
            $category->name = $data->name;
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
     * @return Category $data
     */
    public function getOne($id, Category $category)
    {
        return $category->findOrFail($id);
    }

    /**
     * Method To Update Category
     *
     * @param Object $data
     * @param Integer $id
     * @param Category $category
     * @return Category $category
     */
    public function updateCategory($data, $id, Category $category)
    {
        try {
            $categoryData = $this->getOne($id, $category);

            if (!empty($categoryData)) {
                $categoryData->code = $data->code;
                $categoryData->name = $data->name;
                $categoryData->user_id = $data->user_id;
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
     * @return Boolean true/false
     * @throws \Exception
     */

    public function deleteCategory($id, Category $category)
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
     * @return Object $data
     */
    public function getAll(Category $category)
    {
        return $category->all();
    }

    /**
     * Method For Get Data Category Use name like or code
     *
     * @param String $search
     * @param Category $category
     * @return object list category / empty object
     */
    public function getDataWhere($search = "", Category $category)
    {
        $result = $category->where('code', $search)
            ->orWhere('name', 'LIKE', '%' . $search . '%')
            ->get();
        return $result;
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
    public function getDatable(Category $category, $search, $order_column, $order_dir, $limit_start, $limit_length)
    {
        $result = $category
            ->select('id', 'code', 'name')
            ->where('code', 'LIKE', '%' . $search . '%')
            ->orWhere('name', 'LIKE', '%' . $search . '%')
            ->orderBy($order_column, $order_dir)
            ->offset($limit_start)
            ->limit($limit_length)
            ->get();
        return $result;
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
     * @return mixed
     */
    public function getSearch($search = "", Category $category)
    {
        return $this->getQueryCategory($search, $category)->get();
    }

    /**
     * @param string $search
     * @param Category $category
     * @return mixed
     */
    public function getSearchOne($search = "", Category $category)
    {
        return $this->getQueryCategory($search, $category)->first();
    }

    /**
     * @param string $search
     * @param Category $category
     * @return mixed
     */
    public function getSearchCount($search = "", Category $category)
    {
        return $this->getQueryCategory($search, $category)->count();
    }

    /**
     * @param $code
     * @param Category $category
     * @return mixed
     */
    public function getCategoryByCode($code, Category $category)
    {
        return $category->where('code', $code)->first();
    }

}
