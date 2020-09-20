<?php

/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories;

use WebAppId\Content\Models\File;
use WebAppId\Content\Repositories\Contracts\LanguageRepositoryContract;
use WebAppId\Lazy\Models\Join;
use WebAppId\User\Models\User;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 22/04/20
 * Time: 05.28
 * Class LanguageRepository
 * @package WebAppId\Content\Repositories
 */
class LanguageRepository implements LanguageRepositoryContract
{
    use LanguageRepositoryTrait;

    public function __construct()
    {
        $users = app()->make(Join::class);
        $users->class = User::class;
        $users->foreign = 'user_id';
        $users->type = 'inner';
        $users->primary = 'users.id';
        $this->joinTable['users'] = $users;

        $files = app()->make(Join::class);
        $files->class = File::class;
        $files->foreign = 'image_id';
        $files->type = 'inner';
        $files->primary = 'files.id';
        $this->joinTable['files'] = $files;
    }
}
