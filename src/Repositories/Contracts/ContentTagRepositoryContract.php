<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories\Contracts;

use WebAppId\Content\Models\ContentTag;
use WebAppId\Content\Repositories\Requests\ContentTagRepositoryRequest;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 01:03:14
 * Time: 2020/04/23
 * Class ContentTagRepositoryContract
 * @package WebAppId\Content\Repositories\Contracts
 */
interface ContentTagRepositoryContract
{
    /**
     * @param ContentTagRepositoryRequest $dummyRepositoryClassRequest
     * @param ContentTag $contentTag
     * @return ContentTag|null
     */
    public function store(ContentTagRepositoryRequest $dummyRepositoryClassRequest, ContentTag $contentTag): ?ContentTag;

    /**
     * @param int $content_id
     * @param ContentTag $contentTag
     * @return ContentTag|null
     */
    public function getByContentId(int $content_id, ContentTag $contentTag): ?ContentTag;

    /**
     * @param int $content_id
     * @param ContentTag $contentTag
     * @return bool
     */
    public function deleteByContentId(int $content_id, ContentTag $contentTag): bool;

}
