<?php

namespace WebAppId\Content\Services;

use WebAppId\Content\Repositories\CategoryRepository;
use WebAppId\Content\Services\Responses\CategoryServiceResponse;
use WebAppId\Content\Services\Responses\CategoryServiceResponseList;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 2019-06-07
 * Time: 13:13
 * Class CategoryService
 * @package WebAppId\Content\Services
 */
class CategoryService
{

    /**
     * @param int $id
     * @param CategoryRepository $categoryRepository
     * @param CategoryServiceResponse $categoryServiceResponse
     * @return CategoryServiceResponse
     */
    public function getById(int $id, CategoryRepository $categoryRepository, CategoryServiceResponse $categoryServiceResponse): CategoryServiceResponse
    {
        $result = app()->call([$categoryRepository, 'getById'], ['id' => $id]);
        if ($result != null) {
            $categoryServiceResponse->status = true;
            $categoryServiceResponse->message = 'Data Found';
            $categoryServiceResponse->category = $result;
        } else {
            $categoryServiceResponse->status = false;
            $categoryServiceResponse->message = 'Data Not Found';
        }

        return $categoryServiceResponse;
    }

    /**
     * @param CategoryRepository $categoryRepository
     * @param CategoryServiceResponseList $categoryServiceResponseList
     * @param int $length
     * @param string|null $q
     * @return CategoryServiceResponseList
     */
    public function get(CategoryRepository $categoryRepository, CategoryServiceResponseList $categoryServiceResponseList, int $length = 12, string $q = null): CategoryServiceResponseList
    {
        $result = app()->call([$categoryRepository, 'get'], ['q' => $q]);
        if (count($result) > 0) {
            $categoryServiceResponseList->status = true;
            $categoryServiceResponseList->message = 'Data Found';
            $categoryServiceResponseList->categoryList = $result;
            $categoryServiceResponseList->count = app()->call([$categoryRepository, 'getCount']);
            $categoryServiceResponseList->countFiltered = app()->call([$categoryRepository, 'getCount'], ['q' => $q]);
        } else {
            $categoryServiceResponseList->status = false;
            $categoryServiceResponseList->message = 'Data Not Found';
        }
        return $categoryServiceResponseList;
    }

    /**
     * @param CategoryRepository $categoryRepository
     * @param string|null $q
     * @return int
     */
    public function getCount(CategoryRepository $categoryRepository, string $q = null): int
    {
        return app()->call([$categoryRepository, 'getCount'], ['q' => $q]);
    }
}
