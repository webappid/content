<?php

namespace WebAppId\Content\Repositories;

class ContentCategoryRepository
{
    private $contentCetgory;

    public function __construct(ContentCategory $contentCetgory)
    {
        $this->contentCetgory = $contentCetgory;
    }

    /**
     * Method To Add Data ContentCategory
     *
     * @param Request $data
     * @return Boolean true/false
     */
    public function addContentCategory($data)
    {
        try {
            $this->contentCetgory->content_id    = $data->content_id;
            $this->contentCetgory->categories_id = $data->categories_id;
            $this->contentCetgory->user_id       = $data->user_id;
            $this->contentCetgory->save();

            return $this->contentCetgory;
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
    public function getContentCategoryById($id)
    {
        return $this->contentCetgory->findOrFail($id);
    }

    /**
     * Method To Update ContentCategory
     *
     * @param Request $data
     * @param Integer $id
     * @return Boolean true/false
     */
    public function updateContentCategory($data, $id)
    {
        try {
            $ContentCategory = $this->contentCetgory->getContentCategoryById($id);

            if(! empty($ContentCategory)){
                $ContentCategory->content_id = $data->content_id;
                $ContentCategory->categories_id = $data->categories_id;
                $ContentCategory->user_id = $data->user_id;
                $ContentCategory->save();
                return true;
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
    public function deleteContentCategory($id)
    {
        try {
            $ContentCategory = $this->contentCetgory->getContentCategoryById($id);
            if(! empty($ContentCategory)){
                $ContentCategory->delete();
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
    public function getAll()
    {
        return $this->contentCetgory->all();
    }

    public function getContentCategoryByContentIdAndCategoryId($contentId, $categoryId){
        return $this->contentCetgory
            ->where('content_id',$contentId)
            ->where('categories_id', $categoryId)
            ->first();
    }
}