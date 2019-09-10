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
    /**
     * @var int
     */
    public $content_id;
    /**
     * @var int
     */
    public $file_id;
    /**
     * @var int
     */
    public $user_id;
    /**
     * @var string
     */
    public $description;
    
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
    public function getFileId(): int
    {
        return $this->file_id;
    }
    
    /**
     * @param int $file_id
     */
    public function setFileId(int $file_id): void
    {
        $this->file_id = $file_id;
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