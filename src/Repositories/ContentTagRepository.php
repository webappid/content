<?php

/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\Eloquent\Builder;
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

    /**
     * @param ContentTag $contentTag
     * @return Builder
     */
    protected function getJoin(ContentTag $contentTag): Builder
    {
        return $contentTag
            ->join('contents as contents', 'content_tags.content_id', 'contents.id')
            ->join('tags as tags', 'content_tags.tag_id', 'tags.id')
            ->join('users as users', 'content_tags.user_id', 'users.id');
    }

    /**
     * @return array|string[]
     */
    protected function getColumn(): array
    {
        return [
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
        ];
    }

    /**
     * @inheritDoc
     */
    public function getByContentId(int $content_id, ContentTag $contentTag): ?ContentTag
    {
        return $this->getJoin($contentTag)->where('content_id', $content_id)->first($this->getColumn());
    }

    /**
     * @inheritDoc
     */
    public function deleteByContentId(int $content_id, ContentTag $contentTag): bool
    {
        return $contentTag->where('content_id', $content_id)->delete();
    }
}
