<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use WebAppId\Content\Models\ContentCategory;
use WebAppId\Content\Services\Params\AddContentCategoryParam;

/**
 * Class ContentCategoryRepository
 * @package WebAppId\Content\Repositories
 */
class ContentCategoryRepository
{

    /**
     * Method To Add Data ContentCategory
     *
     * @param AddContentCategoryParam $addContentCategoryParam
     * @param ContentCategory $contentCategory
     * @return ContentCategory true/false
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
     */
    public function deleteContentCategoryByContentId(int $contentId,
                                                     ContentCategory $contentCategory): ?bool
    {
        return $contentCategory->where('content_id', $contentId)->delete();
    }
}