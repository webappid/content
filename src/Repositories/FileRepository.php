<?php

/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories;

use WebAppId\Content\Models\MimeType;
use WebAppId\Content\Repositories\Contracts\FileRepositoryContract;
use WebAppId\Lazy\Models\Join;
use WebAppId\User\Models\User;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 22/04/20
 * Time: 04.13
 * Class FileRepository
 * @package WebAppId\Content\Repositories
 */
class FileRepository implements FileRepositoryContract
{
    use FileRepositoryTrait;

    public function __construct()
    {

        $users = app()->make(Join::class);
        $users->class = User::class;
        $users->foreign = 'creator_id';
        $users->type = 'inner';
        $users->primary = 'users.id';
        $this->joinTable['users'] = $users;

        $user_users = app()->make(Join::class);
        $user_users->class = User::class;
        $user_users->foreign = 'user_id';
        $user_users->type = 'inner';
        $user_users->primary = 'user_users.id';
        $this->joinTable['user_users'] = $user_users;

        $owner_users = app()->make(Join::class);
        $owner_users->class = User::class;
        $owner_users->foreign = 'owner_id';
        $owner_users->type = 'inner';
        $owner_users->primary = 'owner_users.id';
        $this->joinTable['owner_users'] = $owner_users;

        $mime_types = app()->make(Join::class);
        $mime_types->class = MimeType::class;
        $mime_types->foreign = 'mime_type_id';
        $mime_types->type = 'inner';
        $mime_types->primary = 'mime_types.id';
        $this->joinTable['mime_types'] = $mime_types;
    }
}
