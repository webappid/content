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
    /**
     * @var int
     */
    public $content_id;
    /**
     * @var int
     */
    public $category_id;
    /**
     * @var int
     */
    public $user_id;
    
    /**
     * @return int
     */
    public function getContentId(): int
    {
        return $this->content_id;
    }
    
    /**
     * @param int $content_id
     */
    public function setContentId(int $content_id): void
    {
        $this->content_id = $content_id;
    }
    
    /**
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->category_id;
    }
    
    /**
     * @param int $category_id
     */
    public function setCategoryId(int $category_id): void
    {
        $this->category_id = $category_id;
    }
    
    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }
    
    /**
     * @param int $user_id
     */
    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }
    
    
}