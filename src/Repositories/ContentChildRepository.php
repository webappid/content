<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use WebAppId\Content\Models\ContentChild;
use WebAppId\Content\Services\Params\AddContentChildParam;

/**
 * Class ContentChildRepository
 * @package WebAppId\Content\Repositories
 */
class ContentChildRepository
{
    
    /**
     * @param AddContentChildParam $addContentChildParam
     * @param ContentChild $contentChild
     * @return ContentChild|null
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
     * @return mixed
     */
    public function getOne(int $id, ContentChild $contentChild): ?ContentChild
    {
        return $contentChild->findOrFail($id);
    }
    
    /**
     * @param $id
     * @param ContentChild $contentChild
     * @return mixed
     */
    public function getByContentParentId(int $id, ContentChild $contentChild): ?object
    {
        return $contentChild->where('content_parent_id', $id)->get();
    }
    
    /**
     * @param $id
     * @param ContentChild $contentChild
     * @return mixed
     * @throws \Exception
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
     * @return mixed
     */
    public function getAll(ContentChild $contentChild): ?object
    {
        return $contentChild->get();
    }
}