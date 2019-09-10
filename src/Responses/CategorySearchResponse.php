<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-02-10
 * Time: 12:35
 */

namespace WebAppId\Content\Responses;


class CategorySearchResponse extends AbstractDataTableResponse
{
    /**
     * @var object
     */
    public $data;
    
    /**
     * @return object
     */
    public function getData(): ?object
    {
        return $this->data;
    }
    
    /**
     * @param object $data
     */
    public function setData(object $data): void
    {
        $this->data = $data;
    }
    
    
}