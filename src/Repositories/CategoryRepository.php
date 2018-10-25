<?php

namespace WebAppId\Content\Repositories;

use Illuminate\Database\QueryException;
use WebAppId\Content\Models\Category;

class CategoryRepository
{
    private $category;

    /**
     * Method To Add Data Category
     *
     * @param Request $data
     * @return Boolean true/false
     */
    public function addCategory($data, Category $category){
        try{
            $category->code    = $data->code;
            $category->name    = $data->name;
            $category->user_id = $data->user_id;

            $category->save();
            return $category;
        }catch(QueryException $e){
            report($e);
            return false;
        }
    }

    /**
     * Method To Get Data Category
     *
     * @param Integer $id
     * @return Category $data
     */
    public function getOne($id, Category $category){
        return $category->findOrFail($id);
    }

    /**
     * Method To Update Category
     *
     * @param Request $data
     * @param Integer $id
     * @return Boolean true/false
     */
    public function updateCategory($data, $id, Category $category){
        try{
            $categoryData = $this->getOne($id, $category);

            if(!empty($categoryData)){
                $categoryData->code = $data->code;
                $categoryData->name = $data->name;
                $categoryData->user_id = $data->user_id;
                $categoryData->save();
                return $categoryData;
            }else{
                return false;
            }
        }catch(QueryException $e){
            report($e);
            return false;
        }
    }

    /**
     * Method to Delete category Data
     *
     * @param Integer $id
     * @return Boolean true/false
     */
    public function deleteCategory($id, Category $category){
        try{
            $categoryData = $this->getOne($id, $category);
            if(!empty($categoryData)){
                $categoryData->delete();
                return true;
            }else{
                return false;
            }
        }catch(QueryException $e){
            report($e);
            return false;
        }
    }

    /**
     * Get All Category
     *
     * @return Category $data
     */
    public function getAll(Category $category){
        return $category->all();
    }

    /**
     * Method For Get Data Category Use name like or code
     *
     * @param Request $request
     * @return object list category / empty object
     */
    public function getDataWhere($search="", Category $category){
        $result = $category->where('code', $search)
            ->orWhere('name','LIKE','%'.$search.'%')
            ->get();
        return $result;
    }


    public function getDatatable(Category $category, $search, $order_column, $order_dir, $limit_start, $limit_length){
        $result = $category
            ->select('id', 'code', 'name')
            ->where('code','LIKE','%'.$search.'%')
            ->orWhere('name','LIKE','%'.$search.'%')
            ->orderBy($order_column, $order_dir)
            ->offset($limit_start)
            ->limit($limit_length)
            ->get();
        return $result; 
    }

    private function getQueryCategory($search, $category){
       return $category->where('code','LIKE','%'.$search.'%')
            ->orWhere('name','LIKE','%'.$search.'%');
    }

    public function getSearch($search="", Category $category){
        return $this->getQueryCategory($search, $category)->get();
    }

    public function getSearchOne($search="", Category $category){
        return $this->getQueryCategory($search, $category)->first();
    }

    public function getSearchCount($search="", Category $category){
        return $this->getQueryCategory($search, $category)->count();
    }

    public function getCategoryByCode($code, Category $category){
        return $category->where('code', $code)->first();
    }
}
