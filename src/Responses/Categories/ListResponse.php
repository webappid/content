<?php


namespace WebAppId\Content\Responses\Categories;

use WebAppId\DDD\Responses\AbstractResponse;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 2019-06-07
 * Time: 13:42
 * Class ListResponse
 * @package WebAppId\Content\Responses\Categories
 */
class ListResponse extends AbstractResponse
{
    public $list;

    /**
     * @return mixed
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * @param mixed $list
     */
    public function setList($list): void
    {
        $this->list = $list;
    }
}