<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-02-10
 * Time: 21:23
 */

namespace WebAppId\Content\Services\Params;

/**
 * Class AddTimeZoneParam
 * @package WebAppId\Content\Services\Params
 */
class AddTimeZoneParam
{
    /**
     * @var string
     */
    public $code;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $minute;
    /**
     * @var int
     */
    public $user_id;
    
    /**
     * @return string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }
    
    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }
    
    /**
     * @return mixed
     */
    public function getName(): ?string
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
    public function getMinute(): int
    {
        return $this->minute;
    }
    
    /**
     * @param int $minute
     */
    public function setMinute(int $minute): void
    {
        $this->minute = $minute;
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