<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use WebAppId\Content\Models\ContentChild;
use WebAppId\Content\Repositories\Contracts\ContentChildRepositoryContract;
use WebAppId\Content\Repositories\Requests\ContentChildRepositoryRequest;
use WebAppId\Content\Services\Params\AddContentChildParam;
use WebAppId\DDD\Tools\Lazy;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 23/04/20
 * Time: 15.51
 * Class ContentChildRepository
 * @package WebAppId\Content\Repositories
 */
class ContentChildRepository implements ContentChildRepositoryContract
{
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

    protected function getColumn($contentChild)
    {
        return $contentChild
            ->select
            (
                'content_children.id',
                'content_children.content_parent_id',
                'content_children.content_child_id',
                'content_children.user_id',
                'contents.title',
                'contents.code',
                'contents.description',
                'contents.keyword',
                'content_contents.id AS content_id',
                'content_contents.title AS content_title',
                'content_contents.code AS content_code',
                'content_contents.description AS content_description',
                'content_contents.keyword AS content_keyword',
                'users.name',
                'users.email'
            )
            ->join('contents as contents', 'content_children.content_child_id', 'contents.id')
            ->join('contents as content_contents', 'content_children.content_parent_id', 'content_contents.id')
            ->join('users as users', 'content_children.user_id', 'users.id');
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id, ContentChild $contentChild): ?ContentChild
    {
        return $this->getColumn($contentChild)->find($id);
    }

    /**
     * @inheritDoc
     */
    public function getByParentId(int $parentId, ContentChild $contentChild): ?Collection
    {
        return $this->getColumn($contentChild)->where('content_parent_id', $parentId)->get();
    }

    /**
     * @inheritDoc
     */
    public function deleteByParentId(int $parentId, ContentChild $contentChild): bool
    {
        return $contentChild->where('content_parent_id', $parentId)->delete();
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id, ContentChild $contentChild): bool
    {
        return $contentChild->where('id', $id)->delete();
    }

    /**
     * @param AddContentChildParam $addContentChildParam
     * @param ContentChild $contentChild
     * @return ContentChild|null
     * @deprecated
     */
    public function addContentChild(AddContentChildParam $addContentChildParam, ContentChild $contentChild): ?ContentChild
    {
        try {

            $contentChild->content_parent_id = $addContentChildParam->getContentParentId();
            $contentChild->content_child_id = $addContentChildParam->getContentChildId();
            $contentChild->user_id = $addContentChildParam->getUserId();
            $contentChild->save();
            return $contentChild;
        } catch (QueryException $e) {
            report($e);
            return null;
        }
    }

    /**
     * @param $id
     * @param ContentChild $contentChild
     * @return ContentChild|null
     * @deprecated
     */
    public function getOne(int $id, ContentChild $contentChild): ?ContentChild
    {
        return $contentChild->findOrFail($id);
    }

    /**
     * @param $id
     * @param ContentChild $contentChild
     * @return Collection
     * @deprecated
     */
    public function getByContentParentId(int $id, ContentChild $contentChild): Collection
    {
        return $contentChild->where('content_parent_id', $id)->get();
    }

    /**
     * @param $id
     * @param ContentChild $contentChild
     * @return mixed
     * @throws \Exception
     * @deprecated
     */
    public function deleteContentChild(int $id, ContentChild $contentChild): bool
    {
        $contentChild = $this->getOne($id, $contentChild);
        if ($contentChild != null) {
            return $contentChild->delete();
        }
        return true;
    }

    /**
     * @param $id
     * @param ContentChild $contentChild
     * @return bool
     * @deprecated
     */
    public function deleteContentChildByContentId(int $id, ContentChild $contentChild): bool
    {
        DB::beginTransaction();
        $result = true;
        $parentContent = $this->getByContentParentId($id, $contentChild);
        for ($i = 0; $i < count($parentContent); $i++) {
            $result = $parentContent[$i]->delete();
            if (!$result) {
                $result = false;
            }
        }
        if ($result) {
            DB::commit();
            return true;
        } else {
            DB::rollBack();
            return false;
        }
    }

    /**
     * @param ContentChild $contentChild
     * @return Collection
     * @deprecated
     */
    public function getAll(ContentChild $contentChild): Collection
    {
        return $contentChild->get();
    }
}
