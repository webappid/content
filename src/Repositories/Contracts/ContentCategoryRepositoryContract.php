<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\ContentCategory;
use WebAppId\Content\Repositories\Requests\ContentCategoryRepositoryRequest;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 10:56:04
 * Time: 2020/04/23
 * Class ContentCategoryRepositoryContract
 * @package WebAppId\Content\Repositories\Contracts
 */
interface ContentCategoryRepositoryContract
{
    /**
     * @param ContentCategoryRepositoryRequest $dummyRepositoryClassRequest
     * @param ContentCategory $contentCategory
     * @return ContentCategory|null
     */
    public function store(ContentCategoryRepositoryRequest $dummyRepositoryClassRequest, ContentCategory $contentCategory): ?ContentCategory;

    /**
     * @param int $id
     * @param ContentCategoryRepositoryRequest $dummyRepositoryClassRequest
     * @param ContentCategory $contentCategory
     * @return ContentCategory|null
     */
    public function update(int $id, ContentCategoryRepositoryRequest $dummyRepositoryClassRequest, ContentCategory $contentCategory): ?ContentCategory;

    /**
     * @param int $id
     * @param ContentCategory $contentCategory
     * @return ContentCategory|null
     */
    public function getById(int $id, ContentCategory $contentCategory): ?ContentCategory;

    /**
     * @param int $id
     * @param ContentCategory $contentCategory
     * @return bool
     */
    public function delete(int $id, ContentCategory $contentCategory): bool;

    /**
     * @param ContentCategory $contentCategory
     * @param int $length
     * @param string $q
     * @return LengthAwarePaginator
     */
    public function get(ContentCategory $contentCategory, int $length = 12, string $q = null): LengthAwarePaginator;

    /**
     * @param ContentCategory $contentCategory
     * @param string $q
     * @return int
     */
    public function getCount(ContentCategory $contentCategory, string $q = null): int;

    /**
     * @param int $contentId
     * @param ContentCategory $contentCategory
     * @return bool|null
     */
    public function deleteByContentId(int $contentId, ContentCategory $contentCategory): ?bool;

}
