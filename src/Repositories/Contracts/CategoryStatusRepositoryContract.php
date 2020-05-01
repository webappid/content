<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\CategoryStatus;
use WebAppId\Content\Repositories\Requests\CategoryStatusRepositoryRequest;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 00:12:11
 * Time: 2020/04/22
 * Class CategoryStatusRepositoryContract
 * @package App\Repositories\Contracts
 */
interface CategoryStatusRepositoryContract
{
    /**
     * @param CategoryStatusRepositoryRequest $dummyRepositoryClassRequest
     * @param CategoryStatus $categoryStatus
     * @return CategoryStatus|null
     */
    public function store(CategoryStatusRepositoryRequest $dummyRepositoryClassRequest, CategoryStatus $categoryStatus): ?CategoryStatus;

    /**
     * @param int $id
     * @param CategoryStatusRepositoryRequest $dummyRepositoryClassRequest
     * @param CategoryStatus $categoryStatus
     * @return CategoryStatus|null
     */
    public function update(int $id, CategoryStatusRepositoryRequest $dummyRepositoryClassRequest, CategoryStatus $categoryStatus): ?CategoryStatus;

    /**
     * @param int $id
     * @param CategoryStatus $categoryStatus
     * @return CategoryStatus|null
     */
    public function getById(int $id, CategoryStatus $categoryStatus): ?CategoryStatus;

    /**
     * @param int $id
     * @param CategoryStatus $categoryStatus
     * @return bool
     */
    public function delete(int $id, CategoryStatus $categoryStatus): bool;

    /**
     * @param CategoryStatus $categoryStatus
     * @param int $length
     * @param string|null $q
     * @return LengthAwarePaginator
     */
    public function get(CategoryStatus $categoryStatus, int $length = 12, string $q = null): LengthAwarePaginator;

    /**
     * @param CategoryStatus $categoryStatus
     * @param string|null $q
     * @return int
     */
    public function getCount(CategoryStatus $categoryStatus, string $q = null): int;
}
