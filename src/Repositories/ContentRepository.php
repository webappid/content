<?php

/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use WebAppId\Content\Models\Content;
use WebAppId\Content\Repositories\Contracts\ContentRepositoryContract;
use WebAppId\Content\Repositories\Requests\ContentRepositoryRequest;
use WebAppId\DDD\Tools\Lazy;

/**
 * Class ContentRepository
 * @package WebAppId\Content\Repositories
 */
class ContentRepository implements ContentRepositoryContract
{
    /**
     * @inheritDoc
     */
    public function store(ContentRepositoryRequest $contentRepositoryRequest, Content $content): ?Content
    {
        try {
            $content = Lazy::copy($contentRepositoryRequest, $content);
            $content->save();
            $this->cleanCache($content->code);
            return $content;
        } catch (QueryException $queryException) {
            report($queryException);
            dd($queryException);
            return null;
        }
    }

    protected function getColumn($content)
    {
        return $content
            ->select
            (
                'contents.id',
                'contents.title',
                'contents.code',
                'contents.description',
                'contents.keyword',
                'contents.og_title',
                'contents.og_description',
                'contents.default_image',
                'contents.status_id',
                'contents.language_id',
                'contents.time_zone_id',
                'contents.publish_date',
                'contents.additional_info',
                'contents.content',
                'users.id AS creator_id',
                'users.name AS creator_name',
                'user_users.id AS user_id',
                'user_users.name AS user_name',
                'owner_users.id AS owner_id',
                'owner_users.name AS owner_name',
                'files.name AS file_name',
                'files.description AS file_description',
                'files.alt AS file_alt',
                'files.path as file_path',
                'languages.code AS language_code',
                'languages.name AS language_name',
                'owner_users.id AS owner_id',
                'owner_users.name AS owner_name',
                'content_statuses.id AS status_id',
                'content_statuses.name AS status_name',
                'time_zones.id AS timezone_id',
                'time_zones.code AS timezone_code',
                'time_zones.name AS timezone_name',
                'time_zones.minute'
            )
            ->join('users as users', 'contents.creator_id', 'users.id')
            ->join('files as files', 'contents.default_image', 'files.id')
            ->join('languages as languages', 'contents.language_id', 'languages.id')
            ->join('users as owner_users', 'contents.owner_id', 'owner_users.id')
            ->join('content_statuses as content_statuses', 'contents.status_id', 'content_statuses.id')
            ->join('time_zones as time_zones', 'contents.time_zone_id', 'time_zones.id')
            ->join('users as user_users', 'contents.user_id', 'user_users.id');
    }

    /**
     * @inheritDoc
     */
    public function update(string $code, ContentRepositoryRequest $contentRepositoryRequest, Content $content): ?Content
    {
        $content = $this->getByCode($code, $content);
        if ($content != null) {
            try {
                $content = Lazy::copy($contentRepositoryRequest, $content);
                $this->cleanCache($content->code);
                $content->save();
                return $content;
            } catch (QueryException $queryException) {
                report($queryException);
            }
        }
        return $content;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id, Content $content): ?Content
    {
        return $this->getColumn($content)->find($id);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $code, Content $content): bool
    {
        $content = $this->getByCode($code, $content);
        if ($content != null) {
            return $content->delete();
        } else {
            return false;
        }
    }

    private function getWhere(Content $content, int $category_id = null, string $q = null)
    {
        return $this->getColumn($content)
            ->when($category_id != null, function ($query) use ($category_id) {
                return $query->where('category_id', $category_id);
            })
            ->when($q != null, function ($query) use ($q) {
                return $query->where('contents.code', 'LIKE', '%'. $q . '%')
                    ->orWhere('contents.title', 'LIKE', '%' . $q . '%')
                    ->orWhere('contents.description', 'LIKE', '%' . $q . '%')
                    ->orWhere('contents.additional_info', 'LIKE', '%' . $q . '%')
                    ->orWhere('contents.og_title', 'LIKE', '%' . $q . '%');
            });
    }

    /**
     * @inheritDoc
     */
    public function get(Content $content,
                        int $length = 12,
                        int $category_id = null,
                        string $q = null): LengthAwarePaginator
    {
        return $this->getWhere($content, $category_id, $q)
            ->orderBy('contents.id', 'desc')
            ->paginate($length);
    }

    /**
     * @inheritDoc
     */
    public function getCount(Content $content, int $category_id = null, string $q = null): int
    {
        return $this
            ->getWhere($content, $category_id, $q)
            ->count();
    }

    /**
     * @inheritDoc
     */
    public function getByCode(string $code, Content $content): ?Content
    {
        return $content
            ->where('code', $code)
            ->first();
    }

    /**
     * @inheritDoc
     */
    public function updateStatusByCode(string $code, int $status_id, Content $content): ?Content
    {
        $content = $this->getByCode($code, $content);
        if ($content != null) {
            try {
                $content->status_id = $status_id;
                $content->save();
                $this->cleanCache($code);
            } catch (QueryException $e) {
                report($e);
            }
        }

        return $content;
    }

    /**
     * @param string $code
     */
    public function cleanCache(string $code): void
    {
        Cache::forget('content-' . $code);
    }
}
