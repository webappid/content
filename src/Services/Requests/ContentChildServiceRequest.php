<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Services\Requests;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 11:10:45
 * Time: 2020/04/26
 * Class ContentChildServiceRequest
 * @package WebAppId\Content\Services\Requests
 */
class ContentChildServiceRequest
{

    /**
     * @var int
     */
    public $content_parent_id;

    /**
     * @var int
     */
    public $content_child_id;

    /**
     * @var int
     */
    public $user_id;

}
