<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories\Requests;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 04:05:19
 * Time: 2020/04/22
 * Class LanguageRepositoryRequest
 * @package WebAppId\Content\Repositories\Requests
 */
class LanguageRepositoryRequest
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
    public $image_id;

    /**
     * @var int
     */
    public $user_id;

}
