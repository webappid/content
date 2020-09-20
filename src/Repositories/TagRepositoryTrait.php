<?php
/**
 * Created by PhpStorm.
 */

namespace WebAppId\Content\Repositories;


use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\Tag;
use WebAppId\Content\Repositories\Requests\TagRepositoryRequest;
use WebAppId\DDD\Tools\Lazy;
use WebAppId\Lazy\Traits\RepositoryTrait;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 20/09/2020
 * Time: 22.23
 * Class TagRepositoryTrait
 * @package WebAppId\Content\Repositories
 */
trait TagRepositoryTrait
{
    use RepositoryTrait;

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
     * @inheritDoc
     */
    public function update(int $id, TagRepositoryRequest $tagRepositoryRequest, Tag $tag): ?Tag
    {
        $tag = $tag->find($id);
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
        $tag = $tag->find($id);
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