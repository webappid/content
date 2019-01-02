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
     * @param ContentCategory $contentCetgory
     * @return ContentCategory true/false
     */
    public function addContentCategory($data, ContentCategory $contentCetgory)
    {
        try {
            $contentCetgory->content_id    = $data->content_id;
            $contentCetgory->categories_id = $data->categories_id;
            $contentCetgory->user_id       = $data->user_id;
            $contentCetgory->save();
            return $contentCetgory;
        } catch(QueryException $e){
            report($e);
            return null;
        }
    }

    /**
     * Method To Get Data ContentCategory
     *
     * @param Integer $id
     * @param ContentCategory $contentCetgory
     * @return ContentCategory $data
     */
    public function getContentCategoryById($id, ContentCategory $contentCetgory)
    {
        return $contentCetgory->findOrFail($id);
    }

    /**
     * Method To Update ContentCategory
     *
     * @param Object $data
     * @param Integer $id
     * @param ContentCategory $contentCetgory
     * @return ContentCategory $contentCategory
     */
    public function updateContentCategory($data, $id, ContentCategory $contentCetgory)
    {
        try {
            $contentCategoryResult = $this->getContentCategoryById($id, $contentCetgory);

            if(! empty($contentCategoryResult)){
                $contentCategoryResult->content_id = $data->content_id;
                $contentCategoryResult->categories_id = $data->categories_id;
                $contentCategoryResult->user_id = $data->user_id;
                $contentCategoryResult->save();
                return $contentCategoryResult;
            } else {
                return null;
            }
        } catch(QueryException $e){
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
    public function deleteContentCategory($id, ContentCategory $contentCategory)
    {
        try {
            $categoryResult = $this->getContentCategoryById($id, $contentCategory);
            if(! empty($categoryResult)){
                $categoryResult->delete();
                return true;
            } else {
                return false;
            }
        } catch (QueryException $e){
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
    public function getAll(ContentCategory $contentCategory)
    {
        return $contentCategory->all();
    }

    /**
     * @param $contentId
     * @param $categoryId
     * @param ContentCategory $contentCetgory
     * @return mixed
     */
    public function getContentCategoryByContentIdAndCategoryId($contentId, $categoryId, ContentCategory $contentCetgory){
        return $contentCetgory
            ->where('content_id',$contentId)
            ->where('categories_id', $categoryId)
            ->first();
    }
}