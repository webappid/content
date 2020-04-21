<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories\Requests;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 22:58:42
 * Time: 2020/04/21
 * Class CategoryRepositoryRequest
 * @package App\Repositories\Requests
 */
class CategoryRepositoryRequest
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
