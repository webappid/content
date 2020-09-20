<?php

/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories;

use WebAppId\Content\Repositories\Contracts\ContentStatusRepositoryContract;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 22/04/20
 * Time: 04.49
 * Class ContentStatusRepository
 * @package WebAppId\Content\Repositories
 */
class ContentStatusRepository implements ContentStatusRepositoryContract
{
    use ContentStatusRepositoryTrait;
}
