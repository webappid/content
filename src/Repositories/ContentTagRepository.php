<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
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
                'contents.og_title',
                'contents.og_description',
                'contents.default_image',
                'contents.status_id',
                'contents.language_id',
                'contents.time_zone_id',
                'contents.publish_date',
                'contents.additional_info',
                'contents.content',
                'contents.creator_id',
                'contents.owner_id',
                'contents.user_id',
                'tags.id',
                'tags.name',
                'users.id',
                'users.name',
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

    /**
     * @param $response
     * @param ContentTag $contentTag
     * @return ContentTag|null
     * @deprecated
     */
    public function addContentTag($response, ContentTag $contentTag): ?ContentTag
    {
        try {
            $contentTag->content_id = $response->content_id;
            $contentTag->tag_id = $response->tag_id;
            $contentTag->user_id = $response->user_id;
            $contentTag->save();
            return $contentTag;
        } catch (QueryException $e) {
            report($e);
            return null;
        }
    }

    /**
     * @param $contentId
     * @param ContentTag $contentTag
     * @return bool
     * @deprecated
     */
    public function deleteContentTagByContentId($contentId, ContentTag $contentTag): bool
    {
        $resultContentTag = $contentTag->where('content_id', $contentId)->get();
        DB::beginTransaction();
        $result = true;
        if (count($resultContentTag) > 0) {
            for ($i = 0; $i < count($resultContentTag); $i++) {
                if (!$resultContentTag[$i]->delete()) {
                    $result = false;
                    break;
                }
            }
        }
        if ($result) {
            DB::commit();
        } else {
            DB::rollBack();
        }

        return $result;
    }
}
