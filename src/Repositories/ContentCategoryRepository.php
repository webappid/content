<?php

namespace WebAppId\Content\Repositories;

use WebAppId\Content\Models\ContentCategory;

class ContentCategoryRepository
{

    /**
     * Method To Add Data ContentCategory
     *
     * @param Request $data
     * @return Boolean true/false
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
            return false;
        }
    }
    
    /**
     * Method To Get Data ContentCategory
     *
     * @param Integer $id
     * @return ContentCategory $data
     */
    public function getContentCategoryById($id, ContentCategory $contentCetgory)
    {
        return $contentCetgory->findOrFail($id);
    }

    /**
     * Method To Update ContentCategory
     *
     * @param Request $data
     * @param Integer $id
     * @return Boolean true/false
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
                return false;
            }
        } catch(QueryException $e){
            report($e);
            return false;
        }
    }

    /**
     * Method to Delete ContentCategory Data
     *
     * @param Integer $id
     * @return Boolean true/false
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
     * @return ContentCategory $data
     */
    public function getAll(ContentCategory $contentCetgory)
    {
        return $contentCetgory->all();
    }

    public function getContentCategoryByContentIdAndCategoryId($contentId, $categoryId, ContentCategory $contentCetgory){
        return $contentCetgory
            ->where('content_id',$contentId)
            ->where('categories_id', $categoryId)
            ->first();
    }
}