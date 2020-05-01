<?php

/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use WebAppId\Content\Models\Content;
use WebAppId\Content\Repositories\Contracts\ContentRepositoryContract;
use WebAppId\Content\Repositories\Requests\ContentRepositoryRequest;
use WebAppId\Content\Services\Params\AddContentParam;
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
                'users.id',
                'users.name',
                'files.name',
                'files.description',
                'files.alt',
                'files.path',
                'languages.code',
                'languages.name',
                'owner_users.id AS owner_id',
                'owner_users.name AS owner_name',
                'content_statuses.id',
                'content_statuses.name',
                'time_zones.id',
                'time_zones.code',
                'time_zones.name',
                'time_zones.minute',
                'user_users.id AS user_id',
                'user_users.name AS user_name'
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
                return $query->where('contents.code', 'LIKE', $q . '%')
                    ->orWhere('contents.title', 'LIKE', '%' . $q . '%')
                    ->orWhere('contents.description', 'LIKE', '%' . $q . '%')
                    ->orWhere('contents.additional_info', 'LIKE', '%' . $q . '%')
                    ->orWhere('contents.og_title', 'LIKE', '%' . $q . '%');
            });
    }

    /**
     * @inheritDoc
     */
    public function get(Content $content, int $length = 12, int $category_id = null, string $q = null): LengthAwarePaginator
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
     * @param $content
     * @return mixed
     * @deprecated
     */
    protected function getColumnOld($content)
    {
        return $content
            ->select(
                'contents.id AS id',
                'contents.code AS code',
                'contents.title AS title',
                'contents.description',
                'contents.keyword',
                'contents.og_title',
                'contents.og_description',
                'contents.default_image',
                'contents.language_id',
                'contents.status_id',
                'contents.publish_date',
                'contents.additional_info',
                'contents.content',
                'contents.owner_id',
                'contents.user_id',
                'time_zones.minute',
                'time_zones.name AS time_zone_name',
                'status.name AS status_name'
            )
            ->join('content_statuses AS status', 'contents.status_id', '=', 'status.id')
            ->join('languages AS lang', 'contents.language_id', '=', 'lang.id')
            ->join('content_categories AS cc', 'contents.id', '=', 'cc.content_id')
            ->join('time_zones', 'time_zones.id', '=', 'contents.time_zone_id');
    }

    /**
     * Method To Add Data Content
     *
     * @param AddContentParam $addContentParam
     * @param Content $content
     * @return Content|null
     * @deprecated
     */
    public function addContent(AddContentParam $addContentParam, Content $content): ?Content
    {
        try {
            $content->title = $addContentParam->getTitle();
            $content->code = $addContentParam->getCode();
            $content->description = $addContentParam->getDescription();
            $content->keyword = $addContentParam->getKeyword();
            $content->og_title = $addContentParam->getOgTitle();
            $content->og_description = $addContentParam->getOgDescription();
            $content->default_image = $addContentParam->getDefaultImage();
            $content->status_id = $addContentParam->getStatusId();
            $content->language_id = $addContentParam->getLanguageId();
            $content->publish_date = $addContentParam->getPublishDate();
            $content->additional_info = $addContentParam->getAdditionalInfo();
            $content->content = $addContentParam->getContent();
            $content->time_zone_id = $addContentParam->getTimeZoneId();
            $content->owner_id = $addContentParam->getOwnerId();
            $content->user_id = $addContentParam->getUserId();
            $content->creator_id = $addContentParam->getCreatorId();
            $content->save();
            return $content;
        } catch (QueryException $e) {
            report($e);
            return null;
        }
    }

    /**
     * @param AddContentParam $addContentParam
     * @param $code
     * @param Content $content
     * @return Content|null
     * @deprecated
     */
    public function updateContentByCode(AddContentParam $addContentParam,
                                        string $code,
                                        Content $content): ?Content
    {
        $result = $this->getContentByCode($code, $content);
        if ($result != null) {
            try {
                $result->title = $addContentParam->getTitle();
                $result->code = $addContentParam->getCode();
                $result->description = $addContentParam->getDescription();
                $result->keyword = $addContentParam->getKeyword();
                $result->og_title = $addContentParam->getOgTitle();
                $result->og_description = $addContentParam->getOgDescription();
                $result->default_image = $addContentParam->getDefaultImage();
                $result->status_id = $addContentParam->getStatusId();
                $result->language_id = $addContentParam->getLanguageId();
                $result->publish_date = $addContentParam->getPublishDate();
                $result->additional_info = $addContentParam->getAdditionalInfo();
                $result->content = $addContentParam->getContent();
                $result->time_zone_id = $addContentParam->getTimeZoneId();
                $result->owner_id = $addContentParam->getOwnerId();
                $result->user_id = $addContentParam->getUserId();
                $result->save();
                $this->cleanCache($addContentParam->getCode());
                return $result;
            } catch (QueryException $e) {
                report($e);
                return null;
            }
        } else {
            return null;
        }
    }

    /**
     * @param int $category_id
     * @param Content $content
     * @return mixed
     * @deprecated
     */
    private function getQueryAllByCategory(?int $category_id, Content $content)
    {
        return $content
            ->leftJoin('content_categories AS cc', 'contents.id', '=', 'cc.content_id')
            ->when($category_id != null, function ($query) use ($category_id) {
                return $query->where('cc.categories_id', '=', $category_id);
            });
    }

    /**
     * Get All Content
     *
     * @param Content $content
     * @param integer $category_id
     * @return Collection
     * @deprecated
     */
    public function getAll(Content $content, int $category_id = null): Collection
    {
        return $this->getQueryAllByCategory($category_id, $content)->get();
    }

    /**
     * @param integer $category_id
     * @param Content $content
     * @return int
     * @deprecated
     */
    public function getAllCount(Content $content, int $category_id = null): int
    {
        return $this->getQueryAllByCategory($category_id, $content)->count();
    }

    /**
     * @param $code
     * @param Content $content
     * @return Content|null
     * @deprecated
     */
    public function getContentByCode(string $code, Content $content): ?Content
    {
        return $content->where('code', $code)->first();
    }

    /**
     * @param string $search
     * @param $category_id
     * @param $content
     * @return mixed
     * @deprecated
     */
    public function getDataForSearch(int $category_id, Content $content, string $search = "")
    {
        return $this->getColumn($content)
            ->where('cc.categories_id', '=', $category_id)
            ->where(function ($query) use ($search) {
                $query
                    ->where('contents.title', 'LIKE', '%' . $search . '%')
                    ->orWhere('contents.code', 'LIKE', '%' . $search . '%')
                    ->orWhere('contents.description', 'LIKE', '%' . $search . '%')
                    ->orWhere('contents.keyword', 'LIKE', '%' . $search . '%')
                    ->orWhere('contents.og_title', 'LIKE', '%' . $search . '%')
                    ->orWhere('contents.og_description', 'LIKE', '%' . $search . '%')
                    ->orWhere('contents.language_id', 'LIKE', '%' . $search . '%')
                    ->orWhere('contents.status_id', 'LIKE', '%' . $search . '%')
                    ->orWhere('contents.publish_date', 'LIKE', '%' . $search . '%')
                    ->orWhere('contents.additional_info', 'LIKE', '%' . $search . '%')
                    ->orWhere('contents.content', 'LIKE', '%' . $search . '%')
                    ->orWhere('contents.owner_id', 'LIKE', '%' . $search . '%')
                    ->orWhere('contents.user_id', 'LIKE', '%' . $search . '%');
            });
    }

    /**
     * @param int $category_id
     * @param Content $content
     * @param string $search
     * @return Collection
     * @deprecated
     */
    public function getSearch(int $category_id, Content $content, string $search = ""): Collection
    {
        return $this
            ->getDataForSearch($category_id, $content, $search)
            ->get();
    }

    /**
     * @param int $category_id
     * @param Content $content
     * @param int $paginate
     * @param string $search
     * @return LengthAwarePaginator
     * @deprecated
     */
    public function getSearchPaginate(int $category_id, Content $content, int $paginate = 12, string $search = ""): LengthAwarePaginator
    {

        return $this
            ->getDataForSearch($category_id, $content, $search)
            ->paginate($paginate);
    }

    /**
     * @param string $search
     * @param $category_id
     * @param Content $content
     * @return int
     * @deprecated
     */
    public function getSearchCount(int $category_id, Content $content, string $search = ""): int
    {
        return $this
            ->getDataForSearch($category_id, $content, $search)
            ->count();
    }

    /**
     * @param $code
     * @param Content $content
     * @return bool
     * @throws \Exception
     * @deprecated
     */
    public function deleteContentByCode(string $code, Content $content): bool
    {
        $content = $this->getContentByCode($code, $content);
        if ($content != null) {
            try {
                $content->delete();
                $this->cleanCache($code);
                return true;
            } catch (QueryException $e) {
                report($e);
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @param $code
     * @param $status_id
     * @param Content $content
     * @return Content|null
     * @deprecated
     */
    public function updateContentStatusByCode(string $code, int $status_id, Content $content): ?Content
    {
        $content = $this->getContentByCode($code, $content);
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
     * @param $keyword
     * @param $content
     * @return mixed
     * @deprecated
     */
    public function getQueryContentByKeyword(string $keyword, Content $content)
    {
        return $content::where('keyword', $keyword);
    }

    /**
     * @param Content $content
     * @param $keyword
     * @return Collection
     * @deprecated
     */
    public function getContentByKeyword(Content $content, string $keyword): Collection
    {
        return $this->getQueryContentByKeyword($keyword, $content)->get();
    }

    /**
     * @param $keyword
     * @param Content $content
     * @return int
     * @deprecated
     */
    public function getContentByKeywordCount(string $keyword, Content $content): int
    {
        return $this->getQueryContentByKeyword($keyword, $content)->count();
    }

    /**
     * @param $id
     * @param Content $content
     * @return Content|null
     * @deprecated
     */
    public function getContentById(int $id, Content $content): ?Content
    {
        return $content->find($id);
    }

    /**
     * @param string $code
     */
    public function cleanCache(string $code): void
    {
        Cache::forget('content-' . $code);
    }
}
