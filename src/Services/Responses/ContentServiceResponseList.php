<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Services\Responses;

use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\DDD\Responses\AbstractResponse;
use WebAppId\DDD\Responses\AbstractResponseList;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 10:32:46
 * Time: 2020/04/22
 * Class ContentServiceResponseList
 * @package WebAppId\Content\Services\Responses
 */
class ContentServiceResponseList extends AbstractResponseList
{
    /**
     * @var LengthAwarePaginator
     */
    public $contentList;
}
