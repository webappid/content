<?php
/**
 * Created by PhpStorm.
 */

namespace WebAppId\Content\Repositories;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\ContentGallery;
use WebAppId\Content\Repositories\Requests\ContentGalleryRepositoryRequest;
use WebAppId\DDD\Tools\Lazy;
use WebAppId\Lazy\Traits\RepositoryTrait;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 20/09/2020
 * Time: 20.06
 * Class ContentGalleryRepositoryTrait
 * @package WebAppId\Content\Repositories
 */
trait ContentGalleryRepositoryTrait
{
    use RepositoryTrait;

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

    /**
     * @inheritDoc
     */
    public function getByContentId(int $contentId, ContentGallery $contentGallery): Collection
    {
        return $this
            ->getJoin($contentGallery)
            ->where('content_id', $contentId)
            ->get($this->getColumn());
    }

    /**
     * @inheritDoc
     */
    public function deleteByContentId(int $contentId, ContentGallery $contentGallery): bool
    {
        return $contentGallery
            ->where('content_id', $contentId)
            ->delete();
    }

    /**
     * @inheritDoc
     */
    public function get(ContentGallery $contentGallery, int $length = 12, string $q = null): LengthAwarePaginator
    {
        return $this
            ->getJoin($contentGallery)
            ->when($q != null, function ($query) use ($q) {
                return $query->where('content_id', 'LIKE', '%' . $q . '%');
            })
            ->paginate($length, $this->getColumn());
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
}