<?php

/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\ContentCategory;
use WebAppId\Content\Repositories\Contracts\ContentCategoryRepositoryContract;
use WebAppId\Content\Repositories\Requests\ContentCategoryRepositoryRequest;
use WebAppId\Content\Services\Params\AddContentCategoryParam;
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

    protected function getColumn($contentCategory)
    {
        return $contentCategory
            ->select
            (
                'content_categories.id',
                'content_categories.content_id',
                'content_categories.categories_id',
                'content_categories.user_id',
                'categories.code',
                'categories.name',
                'contents.title',
                'contents.code',
                'contents.description',
                'users.name',
                'users.email'
            )
            ->join('categories as categories', 'content_categories.categories_id', 'categories.id')
            ->join('contents as contents', 'content_categories.content_id', 'contents.id')
            ->join('users as users', 'content_categories.user_id', 'users.id');
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
        return $this->getColumn($contentCategory)->find($id);
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
            ->getColumn($contentCategory)
            ->when($q != null, function ($query) use ($q) {
                return $query->where('content_id', 'LIKE', '%' . $q . '%');
            })
            ->paginate($length);
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

    /**
     * Method To Add Data ContentCategory
     *
     * @param AddContentCategoryParam $addContentCategoryParam
     * @param ContentCategory $contentCategory
     * @return ContentCategory true/false
     * @deprecated
     */
    public function addContentCategory(AddContentCategoryParam $addContentCategoryParam, ContentCategory $contentCategory): ?ContentCategory
    {
        try {
            $contentCategory->content_id = $addContentCategoryParam->getContentId();
            $contentCategory->categories_id = $addContentCategoryParam->getCategoryId();
            $contentCategory->user_id = $addContentCategoryParam->getUserId();
            $contentCategory->save();
            return $contentCategory;
        } catch (QueryException $e) {
            report($e);
            return null;
        }
    }

    /**
     * Method To Get Data ContentCategory
     *
     * @param Integer $id
     * @param ContentCategory $contentCategory
     * @return ContentCategory $data
     * @deprecated
     */
    public function getContentCategoryById($id, ContentCategory $contentCategory): ?ContentCategory
    {
        return $contentCategory->findOrFail($id);
    }

    /**
     * Method To Update ContentCategory
     *
     * @param AddContentCategoryParam $addContentCategoryParam
     * @param Integer $id
     * @param ContentCategory $contentCategory
     * @return ContentCategory $contentCategory
     * @deprecated
     */
    public function updateContentCategory(AddContentCategoryParam $addContentCategoryParam, int $id, ContentCategory $contentCategory): ?ContentCategory
    {
        try {
            $contentCategoryResult = $this->getContentCategoryById($id, $contentCategory);

            if (!empty($contentCategoryResult)) {
                $contentCategoryResult->content_id = $addContentCategoryParam->getContentId();
                $contentCategoryResult->categories_id = $addContentCategoryParam->getCategoryId();
                $contentCategoryResult->user_id = $addContentCategoryParam->getUserId();
                $contentCategoryResult->save();
                return $contentCategoryResult;
            } else {
                return null;
            }
        } catch (QueryException $e) {
            report($e);
            return null;
        }
    }

    /**
     * Method to Delete ContentCategory Data
     *
     * @param Integer $id
     * @param ContentCategory $contentCategory
     * @return bool $contentCategory
     * @throws \Exception
     * @deprecated
     */
    public function deleteContentCategory(int $id, ContentCategory $contentCategory): bool
    {
        try {
            $categoryResult = $this->getContentCategoryById($id, $contentCategory);
            if (!empty($categoryResult)) {
                $categoryResult->delete();
                return true;
            } else {
                return false;
            }
        } catch (QueryException $e) {
            report($e);
            return false;
        }
    }

    /**
     * Get All ContentCategory
     *
     * @param ContentCategory $contentCategory
     * @return Collection
     * @deprecated
     */
    public function getAll(ContentCategory $contentCategory): Collection
    {
        return $contentCategory->all();
    }

    /**
     * @param $contentId
     * @param $categoryId
     * @param ContentCategory $contentCategory
     * @return ContentCategory|null
     * @deprecated
     */
    public function getContentCategoryByContentIdAndCategoryId(int $contentId,
                                                               int $categoryId,
                                                               ContentCategory $contentCategory): ?ContentCategory
    {
        return $contentCategory
            ->where('content_id', $contentId)
            ->where('categories_id', $categoryId)
            ->first();
    }

    /**
     * @param int $contentId
     * @param ContentCategory $contentCategory
     * @return bool|null
     * @deprecated
     */
    public function deleteContentCategoryByContentId(int $contentId,
                                                     ContentCategory $contentCategory): ?bool
    {
        return $contentCategory->where('content_id', $contentId)->delete();
    }
}
