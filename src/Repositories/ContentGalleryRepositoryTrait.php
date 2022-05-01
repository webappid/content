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
use WebAppId\Lazy\Tools\Lazy;
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
     * @param ContentGalleryRepositoryRequest $contentGalleryRepositoryRequest
     * @param ContentGallery $contentGallery
     * @return ContentGallery|null
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
     * @param int $contentId
     * @param ContentGallery $contentGallery
     * @return Collection
     */
    public function getByContentId(int $contentId, ContentGallery $contentGallery): Collection
    {
        return $this
            ->getJoin($contentGallery)
            ->where('content_id', $contentId)
            ->get($this->getColumn());
    }

    /**
     * @param int $contentId
     * @param ContentGallery $contentGallery
     * @return bool
     */
    public function deleteByContentId(int $contentId, ContentGallery $contentGallery): bool
    {
        return $contentGallery
            ->where('content_id', $contentId)
            ->delete();
    }

    /**
     * @param ContentGallery $contentGallery
     * @param int $length
     * @param string|null $q
     * @return LengthAwarePaginator
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
     * @param ContentGallery $contentGallery
     * @param string|null $q
     * @return int
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