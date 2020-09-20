<?php
/**
 * Created by PhpStorm.
 */

namespace WebAppId\Content\Repositories;


use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\ContentCategory;
use WebAppId\Content\Repositories\Requests\ContentCategoryRepositoryRequest;
use WebAppId\DDD\Tools\Lazy;
use WebAppId\Lazy\Traits\RepositoryTrait;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 20/09/2020
 * Time: 19.55
 * Class ContentCategoryRepositoryTrait
 * @package WebAppId\Content\Repositories
 */
trait ContentCategoryRepositoryTrait
{
    use RepositoryTrait;

    /**
     * @inheritDoc
     */
    public function store(ContentCategoryRepositoryRequest $contentCategoryRepositoryRequest, ContentCategory $contentCategory): ?ContentCategory
    {
        try {
            $contentCategory = Lazy::copy($contentCategoryRepositoryRequest, $contentCategory);
            $contentCategory->save();
            return $contentCategory;
        } catch (QueryException $queryException) {
            report($queryException);
            return null;
        }
    }

    /**
     * @inheritDoc
     */
    public function update(int $id, ContentCategoryRepositoryRequest $contentCategoryRepositoryRequest, ContentCategory $contentCategory): ?ContentCategory
    {
        $contentCategory = $contentCategory->first($id);
        if ($contentCategory != null) {
            try {
                $contentCategory = Lazy::copy($contentCategoryRepositoryRequest, $contentCategory);
                $contentCategory->save();
                return $contentCategory;
            } catch (QueryException $queryException) {
                report($queryException);
            }
        }
        return $contentCategory;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id, ContentCategory $contentCategory): ?ContentCategory
    {
        return $this
            ->getJoin($contentCategory)
            ->find($id, $this->getColumn());
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id, ContentCategory $contentCategory): bool
    {
        $contentCategory = $contentCategory->find($id);
        if ($contentCategory != null) {
            return $contentCategory->delete();
        } else {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function get(ContentCategory $contentCategory, int $length = 12, string $q = null): LengthAwarePaginator
    {
        return $this
            ->getJoin($contentCategory)
            ->when($q != null, function ($query) use ($q) {
                return $query->where('content_id', 'LIKE', '%' . $q . '%');
            })
            ->paginate($length, $this->getColumn());
    }

    /**
     * @inheritDoc
     */
    public function getCount(ContentCategory $contentCategory, string $q = null): int
    {
        return $contentCategory
            ->when($q != null, function ($query) use ($q) {
                return $query->where('content_id', 'LIKE', '%' . $q . '%');
            })
            ->count();
    }

    /**
     * @inheritDoc
     */
    public function deleteByContentId(int $contentId, ContentCategory $contentCategory): ?bool
    {
        return $contentCategory->where('content_id', $contentId)->delete();
    }
}