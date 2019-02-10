<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-02-10
 * Time: 21:04
 */

namespace WebAppId\Content\Responses;

/**
 * Class ContentSearchResponse
 * @package WebAppId\Content\Responses
 */
class ContentSearchResponse extends AbstractDataTableResponse
{
    private $data;
    
    /**
     * @return array|null
     */
    public function getData(): ?array
    {
        return $this->data;
    }
    
    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }
    
}