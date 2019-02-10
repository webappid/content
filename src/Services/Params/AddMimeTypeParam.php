<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-02-10
 * Time: 22:55
 */

namespace WebAppId\Content\Services\Params;

/**
 * Class AddMimeTypeParam
 * @package WebAppId\Content\Services\Params
 */
class AddMimeTypeParam
{
    private $name;
    private $userId;
    
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