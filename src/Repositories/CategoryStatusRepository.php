<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 04/11/18
 * Time: 03.44
 */

namespace WebAppId\Content\Repositories;


use Illuminate\Database\QueryException;
use WebAppId\Content\Models\CategoryStatus;

class CategoryStatusRepository
{
    /**
     * @param $request
     * @param CategoryStatus $categoryStatus
     * @return null|CategoryStatus
     */
    public function addCategoryStatus($request, CategoryStatus $categoryStatus)
    {
        try {
            $categoryStatus->name = $request->name;
            $categoryStatus->save();
            return $categoryStatus;
        } catch (QueryException $queryException) {
            report($queryException);
            return null;
        }
    }
    
    /**
     * @param $name
     * @param CategoryStatus $categoryStatus
     * @return mixed
     */
    public function getByName($name, CategoryStatus $categoryStatus){
        return $categoryStatus->where('name',$name)->first();
    }
}