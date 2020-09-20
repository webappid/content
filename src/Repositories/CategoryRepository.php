<?php

/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories;

use WebAppId\Content\Models\CategoryStatus;
use WebAppId\Content\Repositories\Contracts\CategoryRepositoryContract;
use WebAppId\Lazy\Models\Join;
use WebAppId\User\Models\User;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 22/04/20
 * Time: 00.15
 * Class CategoryRepository
 * @package WebAppId\Content\Repositories
 */
class CategoryRepository implements CategoryRepositoryContract
{
    use CategoryRepositoryTrait;

    public function __construct()
    {
        $category_statuses = app()->make(Join::class);
        $category_statuses->class = CategoryStatus::class;
        $category_statuses->foreign = 'status_id';
        $category_statuses->type = 'inner';
        $category_statuses->primary = 'category_statuses.id';
        $this->joinTable['category_statuses'] = $category_statuses;

        $users = app()->make(Join::class);
        $users->class = User::class;
        $users->foreign = 'user_id';
        $users->type = 'inner';
        $users->primary = 'users.id';
        $this->joinTable['users'] = $users;
    }
}
