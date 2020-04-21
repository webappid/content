<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\Tag;
use WebAppId\Content\Repositories\Contracts\TagRepositoryContract;
use WebAppId\Content\Repositories\Requests\TagRepositoryRequest;
use WebAppId\Content\Services\Params\AddTagParam;
use WebAppId\DDD\Tools\Lazy;

/**
 * Class TagRepository
 * @package WebAppId\Content\Repositories
 */
class TagRepository implements TagRepositoryContract
{
    /**
     * @inheritDoc
     */
    public function store(TagRepositoryRequest $tagRepositoryRequest, Tag $tag): ?Tag
    {
        try {
            $tag = Lazy::copy($tagRepositoryRequest, $tag);
            $tag->save();
            return $tag;
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
                'tags.id',
                'tags.name',
                'tags.user_id',
                'users.id',
                'users.name'
            )
            ->join('users as users', 'tags.user_id', 'users.id');
    }

    /**
     * @inheritDoc
     */
    public function update(int $id, TagRepositoryRequest $tagRepositoryRequest, Tag $tag): ?Tag
    {
        $tag = $this->getById($id, $tag);
        if ($tag != null) {
            try {
                $tag = Lazy::copy($tagRepositoryRequest, $tag);
                $tag->save();
                return $tag;
            } catch (QueryException $queryException) {
                report($queryException);
            }
        }
        return $tag;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id, Tag $tag): ?Tag
    {
        return $this->getColumn($tag)->find($id);
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id, Tag $tag): bool
    {
        $tag = $this->getById($id, $tag);
        if ($tag != null) {
            return $tag->delete();
        } else {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function get(Tag $tag, int $length = 12): LengthAwarePaginator
    {
        return $this->getColumn($tag)->paginate($length);
    }

    /**
     * @inheritDoc
     */
    public function getCount(Tag $tag): int
    {
        return $tag->count();
    }

    private function getQueryWhere(string $q, Tag $tag)
    {
        return $this->getColumn($tag)
            ->where('tags.name', 'LIKE', '%' . $q . '%');
    }

    /**
     * @inheritDoc
     */
    public function getWhere(string $q, Tag $tag, int $length = 12): LengthAwarePaginator
    {
        return $this
            ->getQueryWhere($q, $tag)
            ->paginate($length);
    }

    /**
     * @inheritDoc
     */
    public function getWhereCount(string $q, Tag $tag, int $length = 12): int
    {
        return $this
            ->getQueryWhere($q, $tag)
            ->count();
    }

    /**
     * @inheritDoc
     */
    public function getByName(string $name, Tag $tag): ?Tag
    {
        return $this->getColumn($tag)
            ->where('tags.name', $name)->first();
    }

    /**
     * @param AddTagParam $addTagParam
     * @param Tag $tag
     * @return Tag|null
     * @deprecated
     */
    public function addTag(AddTagParam $addTagParam, Tag $tag): ?Tag
    {
        try {
            $tag->name = $addTagParam->getName();
            $tag->user_id = $addTagParam->getUserId();
            $tag->save();
            return $tag;
        } catch (QueryException $e) {
            report($e);
            return null;
        }
    }
}
