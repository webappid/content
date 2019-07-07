<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-02-11
 * Time: 15:06
 */

namespace WebAppId\Content\Services\Params;

/**
 * Class AddContentCategoryParam
 * @package WebAppId\Content\Services\Params
 */
class AddContentCategoryParam
{
    public $contentId;
    public $categoryId;
    public $userId;
    
    /**
     * @return int
     */
    public function getContentId(): int
    {
        return $this->contentId;
    }
    
    /**
     * @param int $contentId
     */
    public function setContentId(int $contentId): void
    {
        $this->contentId = $contentId;
    }
    
    /**
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->categoryId;
    }
    
    /**
     * @param int $categoryId
     */
    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }
    
    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }
    
    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }
    
    
}