<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories\Requests;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 10:32:46
 * Time: 2020/04/22
 * Class ContentRepositoryRequest
 * @package WebAppId\Content\Repositories\Requests
 */
class ContentRepositoryRequest
{

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $code;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $keyword;

    /**
     * @var string
     */
    public $og_title;

    /**
     * @var string
     */
    public $og_description;

    /**
     * @var int
     */
    public $default_image;

    /**
     * @var int
     */
    public $status_id;

    /**
     * @var int
     */
    public $language_id;

    /**
     * @var int
     */
    public $time_zone_id;

    /**
     * @var string
     */
    public $publish_date;

    /**
     * @var string
     */
    public $additional_info;

    /**
     * @var string
     */
    public $content;

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
