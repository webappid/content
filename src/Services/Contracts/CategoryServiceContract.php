<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Services\Contracts;

use WebAppId\Content\Repositories\CategoryRepository;
use WebAppId\Content\Services\Responses\CategoryServiceResponse;
use WebAppId\Content\Services\Responses\CategoryServiceResponseList;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 20:32:54
 * Time: 2020/04/25
 * Class CategoryServiceContract
 * @package App\Services\Contracts
 */
interface CategoryServiceContract
{
    /**
     * @param int $id
     * @param CategoryRepository $categoryRepository
     * @param CategoryServiceResponse $categoryServiceResponse
     * @return CategoryServiceResponse
     */
    public function getById(int $id, CategoryRepository $categoryRepository, CategoryServiceResponse $categoryServiceResponse): CategoryServiceResponse;

    /**
     * @param string $q
     * @param CategoryRepository $categoryRepository
     * @param CategoryServiceResponseList $categoryServiceResponseList
     * @param int $length
     * @return CategoryServiceResponseList
     */
    public function get(CategoryRepository $categoryRepository, CategoryServiceResponseList $categoryServiceResponseList,int $length = 12, string $q = null): CategoryServiceResponseList;

    /**
     * @param string $q
     * @param CategoryRepository $categoryRepository
     * @return int
     */
    public function getCount(CategoryRepository $categoryRepository, string $q = null):int;
}
