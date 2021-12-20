<?php

/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories;

use WebAppId\Content\Models\Content;
use WebAppId\Content\Models\Tag;
use WebAppId\Lazy\Models\Join;
use WebAppId\User\Models\User;

/**
 * Class ContentTagRepository
 * @package WebAppId\Content\Repositories
 */
class ContentTagRepository
{
    use ContentTagRepositoryTrait;

    public function __construct()
    {
        $users = app()->make(Join::class);
        $users->class = User::class;
        $users->foreign = 'user_id';
        $users->type = 'inner';
        $users->primary = 'users.id';
        $this->joinTable['users'] = $users;

        $contents = app()->make(Join::class);
        $contents->class = Content::class;
        $contents->foreign = 'content_id';
        $contents->type = 'inner';
        $contents->primary = 'contents.id';
        $this->joinTable['contents'] = $contents;

        $tags = app()->make(Join::class);
        $tags->class = Tag::class;
        $tags->foreign = 'tag_id';
        $tags->type = 'inner';
        $tags->primary = 'tags.id';
        $this->joinTable['tags'] = $tags;
    }

}
