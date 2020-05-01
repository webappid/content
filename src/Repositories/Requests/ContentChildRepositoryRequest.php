<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories\Requests;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 15:48:28
 * Time: 2020/04/23
 * Class ContentChildRepositoryRequest
 * @package WebAppId\Content\Repositories\Requests
 */
class ContentChildRepositoryRequest
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
