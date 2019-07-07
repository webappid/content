<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-02-11
 * Time: 00:09
 */

namespace WebAppId\Content\Services\Params;

/**
 * Class AddFileParam
 * @package WebAppId\Content\Services\Params
 */
class AddFileParam
{
    public $name;
    public $description;
    public $alt;
    public $path;
    public $mimeTypeId;
    public $ownerId;
    public $userId;
    
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    
    /**
     * @return string
     */
    public function getDescription(): string
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
    
    /**
     * @return string
     */
    public function getAlt(): string
    {
        return $this->alt;
    }
    
    /**
     * @param string $alt
     */
    public function setAlt(string $alt): void
    {
        $this->alt = $alt;
    }
    
    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }
    
    /**
     * @param mixed $path
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }
    
    /**
     * @return int
     */
    public function getMimeTypeId(): int
    {
        return $this->mimeTypeId;
    }
    
    /**
     * @param int $mimeTypeId
     */
    public function setMimeTypeId(int $mimeTypeId): void
    {
        $this->mimeTypeId = $mimeTypeId;
    }
    
    /**
     * @return int
     */
    public function getOwnerId(): int
    {
        return $this->ownerId;
    }
    
    /**
     * @param int $ownerId
     */
    public function setOwnerId(int $ownerId): void
    {
        $this->ownerId = $ownerId;
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