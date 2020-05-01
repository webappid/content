<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\ContentStatus;
use WebAppId\Content\Repositories\Requests\ContentStatusRepositoryRequest;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 04:47:50
 * Time: 2020/04/22
 * Class ContentStatusRepositoryContract
 * @package WebAppId\Content\Repositories\Contracts
 */
interface ContentStatusRepositoryContract
{
    /**
     * @param ContentStatusRepositoryRequest $dummyRepositoryClassRequest
     * @param ContentStatus $contentStatus
     * @return ContentStatus|null
     */
    public function store(ContentStatusRepositoryRequest $dummyRepositoryClassRequest, ContentStatus $contentStatus): ?ContentStatus;

    /**
     * @param int $id
     * @param ContentStatusRepositoryRequest $dummyRepositoryClassRequest
     * @param ContentStatus $contentStatus
     * @return ContentStatus|null
     */
    public function update(int $id, ContentStatusRepositoryRequest $dummyRepositoryClassRequest, ContentStatus $contentStatus): ?ContentStatus;

    /**
     * @param int $id
     * @param ContentStatus $contentStatus
     * @return ContentStatus|null
     */
    public function getById(int $id, ContentStatus $contentStatus): ?ContentStatus;

    /**
     * @param int $id
     * @param ContentStatus $contentStatus
     * @return bool
     */
    public function delete(int $id, ContentStatus $contentStatus): bool;

    /**
     * @param ContentStatus $contentStatus
     * @param int $length
     * @param string|null $q
     * @return LengthAwarePaginator
     */
    public function get(ContentStatus $contentStatus, int $length = 12, string $q = null): LengthAwarePaginator;

    /**
     * @param ContentStatus $contentStatus
     * @param string|null $q
     * @return int
     */
    public function getCount(ContentStatus $contentStatus, string $q = null): int;

    /**
     * @param string $name
     * @param ContentStatus $contentStatus
     * @return ContentStatus|null
     */
    public function getByName(string $name, ContentStatus $contentStatus): ?ContentStatus;
}
