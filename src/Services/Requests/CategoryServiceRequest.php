<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Services\Requests;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 20:32:54
 * Time: 2020/04/25
 * Class CategoryServiceRequest
 * @package WebAppId\Content\Services\Requests
 */
class CategoryServiceRequest
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
     * @var int
     */
    public $user_id;

    /**
     * @var int
     */
    public $status_id;

    /**
     * @var int
     */
    public $parent_id;

}
