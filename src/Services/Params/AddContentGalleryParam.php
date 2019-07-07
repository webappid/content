<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-02-11
 * Time: 16:10
 */

namespace WebAppId\Content\Services\Params;


class AddContentGalleryParam
{
    public $contentId;
    public $fileId;
    public $userId;
    public $description;
    
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
    public function getFileId(): int
    {
        return $this->fileId;
    }
    
    /**
     * @param int $fileId
     */
    public function setFileId(int $fileId): void
    {
        $this->fileId = $fileId;
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
    
    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }
    
    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
    
    
}