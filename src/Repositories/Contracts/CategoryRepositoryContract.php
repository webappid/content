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
     * @param string|null $q
     * @return LengthAwarePaginator
     */
    public function get(Category $category, int $length = 12, string $q = null): LengthAwarePaginator;

    /**
     * @param Category $category
     * @param string|null $q
     * @return int
     */
    public function getCount(Category $category, string $q = null): int;

    /**
     * @param string $code
     * @param Category $category
     * @return Category|null
     */
    public function getByCode(string $code, Category $category): ?Category;

    /**
     * @param string $name
     * @param Category $category
     * @return Category|null
     */
    public function getByName(string $name, Category $category): ?Category;
}
