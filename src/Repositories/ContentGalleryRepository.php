<?php

/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories;

use WebAppId\Content\Models\Content;
use WebAppId\Content\Models\File;
use WebAppId\Lazy\Models\Join;
use WebAppId\User\Models\User;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 23/04/20
 * Time: 15.18
 * Class ContentGalleryRepository
 * @package WebAppId\Content\Repositories
 */
class ContentGalleryRepository
{
    use ContentGalleryRepositoryTrait;

    public function __construct()
    {
        $contents = app()->make(Join::class);
        $contents->class = Content::class;
        $contents->foreign = 'content_id';
        $contents->type = 'inner';
        $contents->primary = 'contents.id';
        $this->joinTable['contents'] = $contents;

        $users = app()->make(Join::class);
        $users->class = User::class;
        $users->foreign = 'user_id';
        $users->type = 'inner';
        $users->primary = 'users.id';
        $this->joinTable['users'] = $users;

        $files = app()->make(Join::class);
        $files->class = File::class;
        $files->foreign = 'file_id';
        $files->type = 'inner';
        $files->primary = 'files.id';
        $this->joinTable['files'] = $files;
    }
}
