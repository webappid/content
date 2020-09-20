<?php
/**
 * Created by PhpStorm.
 */

namespace WebAppId\Content\Repositories;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use WebAppId\Content\Models\ContentChild;
use WebAppId\Content\Repositories\Requests\ContentChildRepositoryRequest;
use WebAppId\DDD\Tools\Lazy;
use WebAppId\Lazy\Traits\RepositoryTrait;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 20/09/2020
 * Time: 20.02
 * Class ContentChildRepositoryTrait
 * @package WebAppId\Content\Repositories
 */
trait ContentChildRepositoryTrait
{
    use RepositoryTrait;

    /**
     * @inheritDoc
     */
    public function store(ContentChildRepositoryRequest $contentChildRepositoryRequest, ContentChild $contentChild): ?ContentChild
    {
        try {
            $contentChild = Lazy::copy($contentChildRepositoryRequest, $contentChild);
            $contentChild->save();
            return $contentChild;
        } catch (QueryException $queryException) {
            report($queryException);
            return null;
        }
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id, ContentChild $contentChild): ?ContentChild
    {
        return $this
            ->getJoin($contentChild)
            ->find($id, $this->getColumn());
    }

    /**
     * @inheritDoc
     */
    public function getByParentId(int $parentId, ContentChild $contentChild): ?Collection
    {
        return $this
            ->getJoin($contentChild)
            ->where('content_parent_id', $parentId)
            ->get($this->getColumn());
    }

    /**
     * @inheritDoc
     */
    public function deleteByParentId(int $parentId, ContentChild $contentChild): bool
    {
        return $contentChild
            ->where('content_parent_id', $parentId)
            ->delete();
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id, ContentChild $contentChild): bool
    {
        return $contentChild
            ->where('id', $id)
            ->delete();
    }
}