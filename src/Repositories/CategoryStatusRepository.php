<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories;

use WebAppId\Content\Repositories\Contracts\CategoryStatusRepositoryContract;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 22/04/20
 * Time: 00.15
 * Class CategoryStatusRepository
 * @package WebAppId\Content\Repositories
 */
class CategoryStatusRepository implements CategoryStatusRepositoryContract
{
    use CategoryStatusRepositoryTrait;
}
