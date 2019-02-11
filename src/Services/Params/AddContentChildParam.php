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
    private $contentParentId;
    private $contentChildId;
    private $userId;
    
    /**
     * @return int
     */
    public function getContentParentId(): int
    
    {
        return $this->contentParentId;
    }
    
    /**
     * @param int $contentParentId
     */
    public function setContentParentId(int $contentParentId): void
    {
        $this->contentParentId = $contentParentId;
    }
    
    /**
     * @return int
     */
    public function getContentChildId(): int
    {
        return $this->contentChildId;
    }
    
    /**
     * @param int $contentChildId
     */
    public function setContentChildId(int $contentChildId): void
    {
        $this->contentChildId = $contentChildId;
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