<?php

namespace WebAppId\Content\Repositories;

use Illuminate\Database\QueryException;
use WebAppId\Content\Models\Category;

class CategoryRepository
{
    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }
    /**
     * Method To Add Data Category
     *
     * @param Request $data
     * @return Boolean true/false
     */
    public function addCategory($data){
        try{
            $this->category->code    = $data->code;
            $this->category->name    = $data->name;
            $this->category->user_id = $data->user_id;

            $this->category->save();
            return $this->category;
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
    public function getOne($id){
        return $this->category->findOrFail($id);
    }

    /**
     * Method To Update Category
     *
     * @param Request $data
     * @param Integer $id
     * @return Boolean true/false
     */
    public function updateCategory($data, $id){
        try{
            $categoryData = $this->getOne($id);

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
    public function deleteCategory($id){
        try{
            $categoryData = $this->getOne($id);
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
    public function getAll(){
        return $this->category->all();
    }

    /**
     * Method For Get Data Category Use name like or code
     *
     * @param Request $request
     * @return object list category / empty object
     */
    public function getDataWhere($search=""){
        $result = $this->category->where('code', $search)
            ->orWhere('name','LIKE','%'.$search.'%')
            ->get();
        return $result;
    }


    public function getDatatable($search, $order_column, $order_dir, $limit_start, $limit_length){
        $result = $this->category
            ->select('id', 'code', 'name')
            ->where('code','LIKE','%'.$search.'%')
            ->orWhere('name','LIKE','%'.$search.'%')
            ->orderBy($order_column, $order_dir)
            ->offset($limit_start)
            ->limit($limit_length)
            ->get();
        return $result; 
    }

    private function getQueryCategory($search){
       return $this->category->where('code','LIKE','%'.$search.'%')
            ->orWhere('name','LIKE','%'.$search.'%');
    }

    public function getSearch($search=""){
        return $this->category->getQueryCategory($search)->get();
    }

    public function getSearchOne($search=""){
        return $this->category->getQueryCategory($search)->first();
    }

    public function getSearchCount($search=""){
        return $this->category->getQueryCategory($search)->count();
    }

    public function getCategoryByCode($code){
        return $this->category->where('code', $code)->first();
    }
}
