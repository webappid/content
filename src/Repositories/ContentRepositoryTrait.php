<?php
/**
 * Created by PhpStorm.
 */

namespace WebAppId\Content\Repositories;


use Illuminate\Database\Concerns\BuildsQueries;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use WebAppId\Content\Models\Content;
use WebAppId\Content\Repositories\Requests\ContentRepositoryRequest;
use WebAppId\Lazy\Tools\Lazy;
use WebAppId\Lazy\Traits\RepositoryTrait;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 20/09/2020
 * Time: 20.09
 * Class ContentRepositoryTrait
 * @package WebAppId\Content\Repositories
 */
trait ContentRepositoryTrait
{
    use RepositoryTrait;

    /**
     * @param ContentRepositoryRequest $contentRepositoryRequest
     * @param Content $content
     * @return Content|null
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


    /**
     * @param string $code
     * @param ContentRepositoryRequest $contentRepositoryRequest
     * @param Content $content
     * @return Content|null
     */
    public function update(string $code, ContentRepositoryRequest $contentRepositoryRequest, Content $content): ?Content
    {
        $content = $content->where('code', $code)->first();
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
     * @param int $id
     * @param Content $content
     * @return Content|null
     */
    public function getById(int $id, Content $content): ?Content
    {
        return $this->getJoin($content)->find($id, $this->getColumn());
    }

    /**
     * @param string $code
     * @param Content $content
     * @return bool
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

    /**
     * @param Content $content
     * @param int|null $category_id
     * @param array $categories
     * @param string|null $q
     * @return BuildsQueries|Builder|mixed
     */
    private function getWhere(Content $content, int $category_id = null, $categories = [], string $q = null)
    {
        return $this->getJoin($content)
            ->when($category_id != null, function ($query) use ($category_id) {
                return $query
                    ->join('content_categories', 'content_categories.content_id', 'contents.id')
                    ->where('category_id', $category_id);
            })
            ->when(count($categories) > 0, function ($query) use ($categories) {
                return $query
                    ->join('content_categories', 'content_categories.content_id', 'contents.id')
                    ->whereIn('category_id', $categories);
            })
            ->when($q != null, function ($query) use ($q) {
                return $query->where(function ($query) use ($q) {
                    return $query->where('contents.code', 'LIKE', '%' . $q . '%')
                        ->orWhere('contents.title', 'LIKE', '%' . $q . '%')
                        ->orWhere('contents.description', 'LIKE', '%' . $q . '%')
                        ->orWhere('contents.additional_info', 'LIKE', '%' . $q . '%')
                        ->orWhere('contents.og_title', 'LIKE', '%' . $q . '%');
                });
            });
    }

    /**
     * @param Content $content
     * @param int $length
     * @param int|null $category_id
     * @param array $categories
     * @param string|null $q
     * @return LengthAwarePaginator
     */
    public function get(Content $content,
                        int $length = 12,
                        int $category_id = null,
                        array $categories = [],
                        string $q = null): LengthAwarePaginator
    {
        return $this->getWhere($content, $category_id, $categories, $q)
            ->orderBy('contents.id', 'desc')
            ->paginate($length, $this->getColumn());
    }

    /**
     * @param Content $content
     * @param int|null $category_id
     * @param array $categories
     * @param string|null $q
     * @return int
     */
    public function getCount(Content $content,
                             int $category_id = null,
                             array $categories = [],
                             string $q = null): int
    {
        return $this
            ->getWhere($content, $category_id, $categories, $q)
            ->count();
    }

    /**
     * @param string $code
     * @param Content $content
     * @return Content|null
     */
    public function getByCode(string $code, Content $content): ?Content
    {
        return $this->getJoin($content)
            ->where('contents.code', $code)
            ->first($this->getColumn());
    }

    /**
     * @param string $code
     * @param int $status_id
     * @param Content $content
     * @return Content|null
     */
    public function updateStatusByCode(string $code, int $status_id, Content $content): ?Content
    {
        $content = $content->where('code', $code)->first();
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

    /**
     * @param Content $content
     * @param string|null $q
     * @param int|null $id
     * @return int
     */
    public function getDuplicateTitle(Content $content, string $q = null, int $id = null): int
    {
        return $content
            ->where('contents.code', 'LIKE', $q . '%')
            ->when($id != null, function ($query) use ($id) {
                return $query->where('id', '<>', $id);
            })
            ->count();
    }
}