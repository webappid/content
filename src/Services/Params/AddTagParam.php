<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-02-10
 * Time: 22:35
 */

namespace WebAppId\Content\Services\Params;

/**
 * Class AddTagParam
 * @package WebAppId\Content\Services\Params
 */
class AddTagParam
{
    /**
     * @var string
     */
    public $name;
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