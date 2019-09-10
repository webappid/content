<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-02-11
 * Time: 16:02
 */

namespace WebAppId\Content\Services\Params;


class AddContentChildParam
{
    /**
     * @var int
     */
    public $contentParent_id;
    /**
     * @var
     */
    public $content_child_id;
    /**
     * @var
     */
    public $user_id;
    
    /**
     * @return int
     */
    public function getContentParentId(): int
    
    {
        return $this->contentParent_id;
    }
    
    /**
     * @param int $contentParent_id
     */
    public function setContentParentId(int $contentParent_id): void
    {
        $this->contentParent_id = $contentParent_id;
    }
    
    /**
     * @return int
     */
    public function getContentChildId(): int
    {
        return $this->content_child_id;
    }
    
    /**
     * @param int $content_child_id
     */
    public function setContentChildId(int $content_child_id): void
    {
        $this->content_child_id = $content_child_id;
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