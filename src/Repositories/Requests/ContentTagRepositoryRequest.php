<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories\Requests;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 01:03:14
 * Time: 2020/04/23
 * Class ContentTagRepositoryRequest
 * @package WebAppId\Content\Repositories\Requests
 */
class ContentTagRepositoryRequest
{

    /**
     * @var int
     */
    public $content_id;

    /**
     * @var int
     */
    public $tag_id;

    /**
     * @var int
     */
    public $user_id;

}
