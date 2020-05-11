<?php

/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\ContentCategory;
use WebAppId\Content\Repositories\Contracts\ContentCategoryRepositoryContract;
use WebAppId\Content\Repositories\Requests\ContentCategoryRepositoryRequest;
use WebAppId\DDD\Tools\Lazy;

/**
 * Class ContentCategoryRepository
 * @package WebAppId\Content\Repositories
 */
class ContentCategoryRepository implements ContentCategoryRepositoryContract
{

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
     * @param ContentCategory $contentCategory
     * @return Builder
     */
    protected function getJoin(ContentCategory $contentCategory): Builder
    {
        return $contentCategory
            ->join('categories as categories', 'content_categories.category_id', 'categories.id')
            ->join('contents as contents', 'content_categories.content_id', 'contents.id')
            ->join('users as users', 'content_categories.user_id', 'users.id');
    }

    /**
     * @return array|string[]
     */
    protected function getColumn(): array
    {
        return [
            'content_categories.id',
            'content_categories.content_id',
            'content_categories.category_id',
            'content_categories.user_id',
            'categories.code AS category_code',
            'categories.name AS category_name',
            'contents.title',
            'contents.code AS content_code',
            'contents.description',
            'users.name AS user_name',
            'users.email AS user_email'
        ];

    }

    /**
     * @inheritDoc
     */
    public function update(int $id, ContentCategoryRepositoryRequest $contentCategoryRepositoryRequest, ContentCategory $contentCategory): ?ContentCategory
    {
        $contentCategory = $this->getById($id, $contentCategory);
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
        return $this->getJoin($contentCategory)->find($id, $this->getColumn());
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id, ContentCategory $contentCategory): bool
    {
        $contentCategory = $this->getById($id, $contentCategory);
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
