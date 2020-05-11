<?php

/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\Tag;
use WebAppId\Content\Repositories\Contracts\TagRepositoryContract;
use WebAppId\Content\Repositories\Requests\TagRepositoryRequest;
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

    /**
     * @param Tag $tag
     * @return Builder
     */
    protected function getJoin(Tag $tag): Builder
    {
        return $tag
            ->join('users as users', 'tags.user_id', 'users.id');
    }

    /**
     * @return array|string[]
     */
    protected function getColumn(): array
    {
        return [
            'tags.id',
            'tags.name',
            'tags.user_id',
            'users.name AS user_name'
        ];

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
        return $this->getJoin($tag)->find($id, $this->getColumn());
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
    public function get(Tag $tag, int $length = 12, string $q = null): LengthAwarePaginator
    {
        return $this
            ->getJoin($tag)
            ->when($q != null, function ($query) use ($q) {
                return $query->where('tags.name', 'LIKE', '%' . $q . '%');
            })
            ->paginate($length, $this->getColumn());
    }

    /**
     * @inheritDoc
     */
    public function getCount(Tag $tag, string $q = null): int
    {
        return $tag
            ->when($q != null, function ($query) use ($q) {
                return $query->where('tags.name', 'LIKE', '%' . $q . '%');
            })
            ->count();
    }

    /**
     * @inheritDoc
     */
    public function getByName(string $name, Tag $tag): ?Tag
    {
        return $this->getJoin($tag)
            ->where('tags.name', $name)->first($this->getColumn());
    }
}
