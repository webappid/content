<?php

namespace WebAppId\Content\Services;

use Illuminate\Support\Facades\Cache;
use WebAppId\Content\Repositories\CategoryRepository;
use WebAppId\Content\Responses\Categories\ListResponse;
use WebAppId\Content\Responses\Categories\SearchResponse;
use WebAppId\DDD\Services\BaseService;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 2019-06-07
 * Time: 13:13
 * Class CategoryService
 * @package WebAppId\Content\Services
 */
class CategoryService extends BaseService
{
    /**
     * @param string $code
     * @param CategoryRepository $categoryRepository
     * @param SearchResponse $searchResponse
     * @return SearchResponse
     */
    public function getByCode(string $code, CategoryRepository $categoryRepository, SearchResponse $searchResponse): SearchResponse
    {
        $result = $this->getContainer()->call([$categoryRepository, 'getCategoryByCode'], ['code' => $code]);
        if ($result != null) {
            $searchResponse->setStatus(true);
            $searchResponse->setCategory($result);
        } else {
            $searchResponse->setStatus(false);
        }
        return $searchResponse;
    }

    /**
     * @param string $code
     * @param CategoryRepository $categoryRepository
     * @param ListResponse $listResponse
     * @return ListResponse
     */
    public function getChildByParentCode(string $code, CategoryRepository $categoryRepository, ListResponse $listResponse): ListResponse
    {
        $list = Cache::rememberForever('category-' . $code, function () use ($code, $categoryRepository) {
            $category = $this->getContainer()->call([$categoryRepository, 'getCategoryByCode'], ['code' => $code]);
            return $category->childs;
        });
        if (count($list) > 0) {
            $listResponse->setStatus(true);
            $listResponse->setList($list);
        } else {
            $listResponse->setStatus(false);
        }
        return $listResponse;
    }

    /**
     * @param string $code
     * @param CategoryRepository $categoryRepository
     * @param ListResponse $listResponse
     * @return ListResponse
     */
    public function getChildAndGrandByParentCode(string $code, CategoryRepository $categoryRepository, ListResponse $listResponse): ListResponse
    {
        $list = Cache::rememberForever('category-' . $code, function () use ($code, $categoryRepository) {
            $category = $this->getContainer()->call([$categoryRepository, 'getCategoryByCode'], ['code' => $code]);
            $childs = [];
            for ($i = 0; $i < count($category->childs); $i++) {
                $childs[$i] = $category->childs[$i];
                $childs[$i]['grand'] = $category->childs[$i]->childs;
            }
            return $childs;
        });
        if (count($list) > 0) {
            $listResponse->setStatus(true);
            $listResponse->setList($list);
        } else {
            $listResponse->setStatus(false);
        }
        return $listResponse;
    }

    /**
     * @param string $q
     * @param CategoryRepository $categoryRepository
     * @param ListResponse $listResponse
     * @return ListResponse
     */
    public function getSearchCategory(string $q, CategoryRepository $categoryRepository, ListResponse $listResponse): ListResponse
    {
        $list = $this->getContainer()->call([$categoryRepository, 'getSearch'], ['search' => $q]);
        if (count($list) > 0) {
            $listResponse->setStatus(true);
            $listResponse->setList($list);
        } else {
            $listResponse->setStatus(false);
        }
        return $listResponse;
    }
}