<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories\Requests;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 15:14:13
 * Time: 2020/04/23
 * Class ContentGalleryRepositoryRequest
 * @package WebAppId\Content\Repositories\Requests
 */
class ContentGalleryRepositoryRequest
{

    /**
     * @var int
     */
    public $content_id;

    /**
     * @var int
     */
    public $file_id;

    /**
     * @var int
     */
    public $user_id;

    /**
     * @var string
     */
    public $description;

}
