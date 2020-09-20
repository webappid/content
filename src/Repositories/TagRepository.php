<?php

/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories;

use WebAppId\Content\Repositories\Contracts\TagRepositoryContract;
use WebAppId\Lazy\Models\Join;
use WebAppId\User\Models\User;

/**
 * Class TagRepository
 * @package WebAppId\Content\Repositories
 */
class TagRepository implements TagRepositoryContract
{
    use TagRepositoryTrait;

    public function __construct()
    {
        $users = app()->make(Join::class);
        $users->class = User::class;
        $users->foreign = 'user_id';
        $users->type = 'inner';
        $users->primary = 'users.id';
        $this->joinTable['users'] = $users;
    }
}
