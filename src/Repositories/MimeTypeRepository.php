<?php


/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories;

use WebAppId\Lazy\Models\Join;
use WebAppId\User\Models\User;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 22/04/20
 * Time: 04.56
 * Class MimeTypeRepository
 * @package WebAppId\Content\Repositories
 */
class MimeTypeRepository
{
    use MimeTypeRepositoryTrait;

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
