<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories\Requests;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 04:54:28
 * Time: 2020/04/22
 * Class TimeZoneRepositoryRequest
 * @package WebAppId\Content\Repositories\Requests
 */
class TimeZoneRepositoryRequest
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
    public $minute;

    /**
     * @var int
     */
    public $user_id;

}
