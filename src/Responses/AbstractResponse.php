<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-02-09
 * Time: 11:12
 */

namespace WebAppId\Content\Responses;


abstract class AbstractResponse
{
    private $status;
    private $message;
    
    /**
     * @return bool
     */
    public function getStatus(): bool
    {
        return $this->status;
    }
    
    /**
     * @param bool $status
     */
    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }
    
    /**
     * @return mixed
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }
    
    /**
     * @param mixed $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
    
    
}