<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Services\Responses;

use WebAppId\Content\Models\Content;
use WebAppId\DDD\Responses\AbstractResponse;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 10:32:46
 * Time: 2020/04/22
 * Class ContentServiceResponse
 * @package App\Services\Responses
 */
class ContentServiceResponse extends AbstractResponse
{
    /**
     * @var Content
     */
    public $content;

    /**
     * @var array
     */
    public $galleries = [];

    /**
     * @var array
     */
    public $categories = [];

    /**
     * @var array
     */
    public $children=[];

}
