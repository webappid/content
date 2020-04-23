<?php

/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\ContentGallery;
use WebAppId\Content\Repositories\Contracts\ContentGalleryRepositoryContract;
use WebAppId\Content\Repositories\Requests\ContentGalleryRepositoryRequest;
use WebAppId\Content\Services\Params\AddContentGalleryParam;
use WebAppId\DDD\Tools\Lazy;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 23/04/20
 * Time: 15.18
 * Class ContentGalleryRepository
 * @package WebAppId\Content\Repositories
 */
class ContentGalleryRepository implements ContentGalleryRepositoryContract
{
    /**
     * @inheritDoc
     */
    public function store(ContentGalleryRepositoryRequest $contentGalleryRepositoryRequest, ContentGallery $contentGallery): ?ContentGallery
    {
        try {
            $contentGallery = Lazy::copy($contentGalleryRepositoryRequest, $contentGallery);
            $contentGallery->save();
            return $contentGallery;
        } catch (QueryException $queryException) {
            report($queryException);
            return null;
        }
    }

    protected function getColumn($contentGallery)
    {
        return $contentGallery
            ->select
            (
                'content_galleries.id',
                'content_galleries.content_id',
                'content_galleries.file_id',
                'content_galleries.user_id',
                'content_galleries.description',
                'contents.title',
                'contents.code',
                'contents.description',
                'contents.keyword',
                'files.name',
                'files.description',
                'files.alt',
                'files.path',
                'files.mime_type_id',
                'users.name',
                'users.email'
            )
            ->join('contents as contents', 'content_galleries.content_id', 'contents.id')
            ->join('files as files', 'content_galleries.file_id', 'files.id')
            ->join('users as users', 'content_galleries.user_id', 'users.id');
    }

    /**
     * @inheritDoc
     */
    public function getByContentId(int $contentId, ContentGallery $contentGallery): Collection
    {
        return $this->getColumn($contentGallery)->where('content_id', $contentId)->get();
    }

    /**
     * @inheritDoc
     */
    public function deleteByContentId(int $contentId, ContentGallery $contentGallery): bool
    {
        return $contentGallery->where('content_id', $contentId)->delete();
    }

    /**
     * @inheritDoc
     */
    public function get(ContentGallery $contentGallery, int $length = 12, string $q = null): LengthAwarePaginator
    {
        return $this
            ->getColumn($contentGallery)
            ->when($q != null, function ($query) use ($q) {
                return $query->where('content_id', 'LIKE', '%' . $q . '%');
            })
            ->paginate($length);
    }

    /**
     * @inheritDoc
     */
    public function getCount(ContentGallery $contentGallery, string $q = null): int
    {
        return $contentGallery
            ->when($q != null, function ($query) use ($q) {
                return $query->where('content_id', 'LIKE', '%' . $q . '%');
            })
            ->count();
    }

    /**
     * @param AddContentGalleryParam $addContentGalleryParam
     * @param ContentGallery $contentGallery
     * @return ContentGallery|null
     * @deprecated
     */
    public function addContentGallery(AddContentGalleryParam $addContentGalleryParam, ContentGallery $contentGallery): ?ContentGallery
    {
        try {
            $contentGallery->content_id = $addContentGalleryParam->getContentId();
            $contentGallery->file_id = $addContentGalleryParam->getFileId();
            $contentGallery->user_id = $addContentGalleryParam->getUserId();
            $contentGallery->description = $addContentGalleryParam->getDescription();
            $contentGallery->save();
            return $contentGallery;
        } catch (QueryException $e) {
            report($e);
            return null;
        }
    }

    /**
     * @param $content_id
     * @param ContentGallery $contentGallery
     * @return Collection
     * @deprecated
     */
    public function getContentGalleryByContentId(int $content_id, ContentGallery $contentGallery): Collection
    {
        return $contentGallery->where('content_id', $content_id)->get();
    }

    /**
     * @param $content_id
     * @param ContentGallery $contentGallery
     * @return bool
     * @deprecated
     */
    public function deleteContentGalleryByContentId(int $content_id, ContentGallery $contentGallery): bool
    {
        try {
            $contentGalleries = $this->getContentGalleryByContentId($content_id, $contentGallery);
            for ($i = 0; $i < count($contentGalleries); $i++) {
                $contentGalleries[0]->delete();
            }
            return true;
        } catch (QueryException $e) {
            report($e);
            return false;
        }
    }
}
