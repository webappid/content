<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use WebAppId\Content\Models\ContentChild;
use WebAppId\Content\Repositories\Requests\ContentChildRepositoryRequest;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 15:48:28
 * Time: 2020/04/23
 * Class ContentChildRepositoryContract
 * @package WebAppId\Content\Repositories\Contracts
 */
interface ContentChildRepositoryContract
{
    /**
     * @param ContentChildRepositoryRequest $dummyRepositoryClassRequest
     * @param ContentChild $contentChild
     * @return ContentChild|null
     */
    public function store(ContentChildRepositoryRequest $dummyRepositoryClassRequest, ContentChild $contentChild): ?ContentChild;

    /**
     * @param int $id
     * @param ContentChild $contentChild
     * @return ContentChild|null
     */
    public function getById(int $id, ContentChild $contentChild): ?ContentChild;

    /**
     * @param int $parentId
     * @param ContentChild $contentChild
     * @return Collection|null
     */
    public function getByParentId(int $parentId, ContentChild $contentChild): ?Collection;

    /**
     * @param int $id
     * @param ContentChild $contentChild
     * @return bool
     */
    public function delete(int $id, ContentChild $contentChild): bool;

    /**
     * @param int $parentId
     * @param ContentChild $contentChild
     * @return bool
     */
    public function deleteByParentId(int $parentId, ContentChild $contentChild): bool;

}
