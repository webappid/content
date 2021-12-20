<?php

/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories;

use WebAppId\Content\Models\Category;
use WebAppId\Content\Models\Content;
use WebAppId\Lazy\Models\Join;
use WebAppId\User\Models\User;

/**
 * Class ContentCategoryRepository
 * @package WebAppId\Content\Repositories
 */
class ContentCategoryRepository
{
    use ContentCategoryRepositoryTrait;

    public function __construct()
    {
        $categories = app()->make(Join::class);
        $categories->class = Category::class;
        $categories->foreign = 'category_id';
        $categories->type = 'inner';
        $categories->primary = 'categories.id';
        $this->joinTable['categories'] = $categories;

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
    }
}
