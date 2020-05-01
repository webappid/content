<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Services\Requests;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 10:32:46
 * Time: 2020/04/22
 * Class ContentServiceRequest
 * @package WebAppId\Content\Services\Requests
 */
class ContentServiceRequest
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

    /**
     * @var array
     */
    public $categories = [];

    /**
     * @var int
     */
    public $parent_id;

    /**
     * @var array
     */
    public $galleries = [];

}
