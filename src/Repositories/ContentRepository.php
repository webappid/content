<?php

/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories;

use WebAppId\Content\Models\ContentStatus;
use WebAppId\Content\Models\File;
use WebAppId\Content\Models\Language;
use WebAppId\Content\Models\TimeZone;
use WebAppId\Lazy\Models\Join;
use WebAppId\User\Models\User;

/**
 * Class ContentRepository
 * @package WebAppId\Content\Repositories
 */
class ContentRepository
{
    use ContentRepositoryTrait;

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

        $files = app()->make(Join::class);
        $files->class = File::class;
        $files->foreign = 'default_image';
        $files->type = 'inner';
        $files->primary = 'files.id';
        $this->joinTable['files'] = $files;

        $languages = app()->make(Join::class);
        $languages->class = Language::class;
        $languages->foreign = 'language_id';
        $languages->type = 'inner';
        $languages->primary = 'languages.id';
        $this->joinTable['languages'] = $languages;

        $content_statuses = app()->make(Join::class);
        $content_statuses->class = ContentStatus::class;
        $content_statuses->foreign = 'status_id';
        $content_statuses->type = 'inner';
        $content_statuses->primary = 'content_statuses.id';
        $this->joinTable['content_statuses'] = $content_statuses;

        $time_zones = app()->make(Join::class);
        $time_zones->class = TimeZone::class;
        $time_zones->foreign = 'time_zone_id';
        $time_zones->type = 'inner';
        $time_zones->primary = 'time_zones.id';
        $this->joinTable['time_zones'] = $time_zones;

    }
}
