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
use WebAppId\Content\Services\Params\AddCategoryStatusParam;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 2019-05-31
 * Time: 14:02
 * Class CategoryStatusRepository
 * @package WebAppId\Content\Repositories
 */
class CategoryStatusRepository
{
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