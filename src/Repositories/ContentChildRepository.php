<?php

/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories;

use WebAppId\Content\Models\Content;
use WebAppId\Content\Repositories\Contracts\ContentChildRepositoryContract;
use WebAppId\Lazy\Models\Join;
use WebAppId\User\Models\User;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 23/04/20
 * Time: 15.51
 * Class ContentChildRepository
 * @package WebAppId\Content\Repositories
 */
class ContentChildRepository implements ContentChildRepositoryContract
{
    use ContentChildRepositoryTrait;

    public function __construct()
    {

        $contents = app()->make(Join::class);
        $contents->class = Content::class;
        $contents->foreign = 'content_child_id';
        $contents->type = 'inner';
        $contents->primary = 'contents.id';
        $this->joinTable['contents'] = $contents;

        $content_contents = app()->make(Join::class);
        $content_contents->class = Content::class;
        $content_contents->foreign = 'content_parent_id';
        $content_contents->type = 'inner';
        $content_contents->primary = 'content_contents.id';
        $this->joinTable['content_contents'] = $content_contents;

        $users = app()->make(Join::class);
        $users->class = User::class;
        $users->foreign = 'user_id';
        $users->type = 'inner';
        $users->primary = 'users.id';
        $this->joinTable['users'] = $users;
    }
}
