<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories\Requests;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 04:10:49
 * Time: 2020/04/22
 * Class FileRepositoryRequest
 * @package App\Repositories\Requests
 */
class FileRepositoryRequest
{

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $alt;

    /**
     * @var string
     */
    public $path;

    /**
     * @var int
     */
    public $mime_type_id;

    /**
     * @var int
     */
    public $creator_id;

    /**
     * @var int
     */
    public $owner_id;

    /**
     * @var int
     */
    public $user_id;

}
