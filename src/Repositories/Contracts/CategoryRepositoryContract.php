<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\Category;
use WebAppId\Content\Repositories\Requests\CategoryRepositoryRequest;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 22:58:42
 * Time: 2020/04/21
 * Class CategoryRepositoryContract
 * @package App\Repositories\Contracts
 */
interface CategoryRepositoryContract
{
    /**
     * @param CategoryRepositoryRequest $dummyRepositoryClassRequest
     * @param Category $category
     * @return Category|null
     */
    public function store(CategoryRepositoryRequest $dummyRepositoryClassRequest, Category $category): ?Category;

    /**
     * @param int $id
     * @param CategoryRepositoryRequest $dummyRepositoryClassRequest
     * @param Category $category
     * @return Category|null
     */
    public function update(int $id, CategoryRepositoryRequest $dummyRepositoryClassRequest, Category $category): ?Category;

    /**
     * @param int $id
     * @param Category $category
     * @return Category|null
     */
    public function getById(int $id, Category $category): ?Category;

    /**
     * @param int $id
     * @param Category $category
     * @return bool
     */
    public function delete(int $id, Category $category): bool;

    /**
     * @param Category $category
     * @param int $length
     * @return LengthAwarePaginator
     */
    public function get(Category $category, int $length = 12): LengthAwarePaginator;

    /**
     * @param Category $category
     * @return int
     */
    public function getCount(Category $category): int;

    /**
     * @param string $q
     * @param Category $category
     * @param int $length
     * @return LengthAwarePaginator
     */
    public function getWhere(string $q, Category $category, int $length = 12): LengthAwarePaginator;

    /**
     * @param string $q
     * @param Category $category
     * @param int $length
     * @return int
     */
    public function getWhereCount(string $q, Category $category, int $length = 12): int;

    /**
     * @param string $code
     * @param Category $category
     * @return Category|null
     */
    public function getByCode(string $code, Category $category): ?Category;
}
