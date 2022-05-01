<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Services\Responses;

use WebAppId\Content\Models\Tag;
use WebAppId\SmartResponse\Responses\AbstractResponse;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 17:09:16
 * Time: 2020/07/28
 * Class TagServiceResponse
 * @package WebAppId\Content\Services\Responses
 */
class TagServiceResponse extends AbstractResponse
{
    /**
     * @var Tag
     */
    public $tag;
}
