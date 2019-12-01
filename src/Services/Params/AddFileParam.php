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
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $description;
    /**
     * @var string
     */
    public $alt;
    /**
     * @var string
     */
    public $path;
    /**
     * @var int
     */
    public $mime_type_id;
    /**
     * @var
     */
    public $creator_id;
    /**
     * @var int
     */
    public $owner_id;
    /**
     * @var int
     */
    public $user_id;
    
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
        return $this->mime_type_id;
    }
    
    /**
     * @param int $mime_type_id
     */
    public function setMimeTypeId(int $mime_type_id): void
    {
        $this->mime_type_id = $mime_type_id;
    }
    
    /**
     * @return int
     */
    public function getOwnerId(): int
    {
        return $this->owner_id;
    }
    
    /**
     * @param int $owner_id
     */
    public function setOwnerId(int $owner_id): void
    {
        $this->owner_id = $owner_id;
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
     * @return mixed
     */
    public function getCreatorId()
    {
        return $this->creator_id;
    }

    /**
     * @param mixed $creator_id
     */
    public function setCreatorId($creator_id): void
    {
        $this->creator_id = $creator_id;
    }
}