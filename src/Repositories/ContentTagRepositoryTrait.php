<?php
/**
 * Created by PhpStorm.
 */

namespace WebAppId\Content\Repositories;


use Illuminate\Database\QueryException;
use WebAppId\Content\Models\ContentTag;
use WebAppId\Content\Repositories\Requests\ContentTagRepositoryRequest;
use WebAppId\Lazy\Tools\Lazy;
use WebAppId\Lazy\Traits\RepositoryTrait;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 20/09/2020
 * Time: 20.23
 * Class ContentTagRepositoryTrait
 * @package WebAppId\Content\Repositories
 */
trait ContentTagRepositoryTrait
{
    use RepositoryTrait;

    /**
     * @param ContentTagRepositoryRequest $contentTagRepositoryRequest
     * @param ContentTag $contentTag
     * @return ContentTag|null
     */
    public function store(ContentTagRepositoryRequest $contentTagRepositoryRequest, ContentTag $contentTag): ?ContentTag
    {
        try {
            $contentTag = Lazy::copy($contentTagRepositoryRequest, $contentTag);
            $contentTag->save();
            return $contentTag;
        } catch (QueryException $queryException) {
            report($queryException);
            return null;
        }
    }

    /**
     * @param int $content_id
     * @param ContentTag $contentTag
     * @return ContentTag|null
     */
    public function getByContentId(int $content_id, ContentTag $contentTag): ?ContentTag
    {
        return $this->getJoin($contentTag)->where('content_id', $content_id)->first($this->getColumn());
    }

    /**
     * @param int $content_id
     * @param ContentTag $contentTag
     * @return bool
     */
    public function deleteByContentId(int $content_id, ContentTag $contentTag): bool
    {
        return $contentTag->where('content_id', $content_id)->delete();
    }
}