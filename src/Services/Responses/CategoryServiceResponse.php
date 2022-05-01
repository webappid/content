<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Services\Responses;

use WebAppId\Content\Models\Category;
use WebAppId\SmartResponse\Responses\AbstractResponse;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 20:32:54
 * Time: 2020/04/25
 * Class CategoryServiceResponse
 * @package WebAppId\Content\Services\Responses
 */
class CategoryServiceResponse extends AbstractResponse
{
    /**
     * @var Category
     */
    public $category;
}
