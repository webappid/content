<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\QueryException;
use WebAppId\Content\Models\ContentCategory;

/**
 * Class ContentCategoryRepository
 * @package WebAppId\Content\Repositories
 */
class ContentCategoryRepository
{
    
    /**
     * Method To Add Data ContentCategory
     *
     * @param Object $data
     * @param ContentCategory $contentCategory
     * @return ContentCategory true/false
     */
    public function addContentCategory($data, ContentCategory $contentCategory): ?ContentCategory
    {
        try {
            $contentCategory->content_id = $data->content_id;
            $contentCategory->categories_id = $data->categories_id;
            $contentCategory->user_id = $data->user_id;
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
     * @param Object $data
     * @param Integer $id
     * @param ContentCategory $contentCategory
     * @return ContentCategory $contentCategory
     */
    public function updateContentCategory($data, $id, ContentCategory $contentCategory): ?ContentCategory
    {
        try {
            $contentCategoryResult = $this->getContentCategoryById($id, $contentCategory);
    
            if (!empty($contentCategoryResult)) {
                $contentCategoryResult->content_id = $data->content_id;
                $contentCategoryResult->categories_id = $data->categories_id;
                $contentCategoryResult->user_id = $data->user_id;
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
    public function deleteContentCategory($id, ContentCategory $contentCategory): bool
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
     * @return \Illuminate\Database\Eloquent\Collection|ContentCategory[] $data
     */
    public function getAll(ContentCategory $contentCategory): ?object
    {
        return $contentCategory->all();
    }
    
    /**
     * @param $contentId
     * @param $categoryId
     * @param ContentCategory $contentCategory
     * @return mixed
     */
    public function getContentCategoryByContentIdAndCategoryId($contentId,
                                                               $categoryId,
                                                               ContentCategory $contentCategory): ?ContentCategory
    {
        return $contentCategory
            ->where('content_id', $contentId)
            ->where('categories_id', $categoryId)
            ->first();
    }
}