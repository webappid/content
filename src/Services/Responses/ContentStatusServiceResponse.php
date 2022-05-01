<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Services\Responses;

use WebAppId\Content\Models\ContentStatus;
use WebAppId\SmartResponse\Responses\AbstractResponse;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 04:47:50
 * Time: 2020/04/22
 * Class ContentStatusServiceResponse
 * @package App\Services\Responses
 */
class ContentStatusServiceResponse extends AbstractResponse
{
    /**
     * @var ContentStatus
     */
    public $contentStatus;
}
