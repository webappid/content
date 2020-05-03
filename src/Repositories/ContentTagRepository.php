<?php

/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\QueryException;
use WebAppId\Content\Models\ContentTag;
use WebAppId\Content\Repositories\Contracts\ContentTagRepositoryContract;
use WebAppId\Content\Repositories\Requests\ContentTagRepositoryRequest;
use WebAppId\DDD\Tools\Lazy;

/**
 * Class ContentTagRepository
 * @package WebAppId\Content\Repositories
 */
class ContentTagRepository implements ContentTagRepositoryContract
{
    /**
     * @inheritDoc
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

    protected function getColumn($contentTag)
    {
        return $contentTag
            ->select
            (
                'content_tags.id',
                'content_tags.content_id',
                'content_tags.tag_id',
                'content_tags.user_id',
                'contents.title',
                'contents.code',
                'contents.description',
                'contents.keyword',
                'tags.name AS tag_name',
                'users.name AS user_name',
                'users.email'
            )
            ->join('contents as contents', 'content_tags.content_id', 'contents.id')
            ->join('tags as tags', 'content_tags.tag_id', 'tags.id')
            ->join('users as users', 'content_tags.user_id', 'users.id');
    }

    /**
     * @inheritDoc
     */
    public function getByContentId(int $content_id, ContentTag $contentTag): ?ContentTag
    {
        return $this->getColumn($contentTag)->where('content_id', $content_id)->first();
    }

    /**
     * @inheritDoc
     */
    public function deleteByContentId(int $content_id, ContentTag $contentTag): bool
    {
        $contentTag = $this->getByContentId($content_id, $contentTag);
        if ($contentTag != null) {
            return $contentTag->delete();
        } else {
            return false;
        }
    }
}
