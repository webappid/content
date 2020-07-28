<?php

namespace WebAppId\Content\Services;

use WebAppId\Content\Repositories\CategoryRepository;
use WebAppId\Content\Services\Contracts\CategoryServiceContract;
use WebAppId\Content\Services\Responses\CategoryServiceResponse;
use WebAppId\Content\Services\Responses\CategoryServiceResponseList;
use WebAppId\DDD\Services\BaseService;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 2019-06-07
 * Time: 13:13
 * Class CategoryService
 * @package WebAppId\Content\Services
 */
class CategoryService extends BaseService implements CategoryServiceContract
{

    /**
     * @inheritDoc
     */
    public function getById(int $id, CategoryRepository $categoryRepository, CategoryServiceResponse $categoryServiceResponse): CategoryServiceResponse
    {
        $result = $this->container->call([$categoryRepository, 'getById'], ['id' => $id]);
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
     * @inheritDoc
     */
    public function get(CategoryRepository $categoryRepository, CategoryServiceResponseList $categoryServiceResponseList, int $length = 12, string $q = null): CategoryServiceResponseList
    {
        $result = $this->container->call([$categoryRepository, 'get'], ['q' => $q]);
        if (count($result) > 0) {
            $categoryServiceResponseList->status = true;
            $categoryServiceResponseList->message = 'Data Found';
            $categoryServiceResponseList->categoryList = $result;
            $categoryServiceResponseList->count = $this->container->call([$categoryRepository, 'getCount']);
            $categoryServiceResponseList->countFiltered = $this->container->call([$categoryRepository, 'getCount'], ['q' => $q]);
        } else {
            $categoryServiceResponseList->status = false;
            $categoryServiceResponseList->message = 'Data Not Found';
        }
        return $categoryServiceResponseList;
    }

    /**
     * @inheritDoc
     */
    public function getCount(CategoryRepository $categoryRepository, string $q = null): int
    {
        return $this->container->call([$categoryRepository, 'getCount'], ['q' => $q]);
    }
}
