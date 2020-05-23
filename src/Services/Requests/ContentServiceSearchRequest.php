<?php
/**
 * Created by PhpStorm.
 * User: galih
 * Date: 2019-03-04
 * Time: 09:20
 */

namespace WebAppId\Content\Services\Requests;


class ContentServiceSearchRequest
{
    /**
     * @var string
     */
    public $q = "";
    /**
     * @var string
     */
    public $category = "";

    /**
     * @var array
     */
    public $categories = [];
}
