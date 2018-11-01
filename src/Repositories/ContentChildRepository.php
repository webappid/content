<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use WebAppId\Content\Models\ContentChild;

/**
 * Class ContentChildRepository
 * @package WebAppId\Content\Repositories
 */

class ContentChildRepository
{

    /**
     * @param $request
     * @param ContentChild $contentChild
     * @return bool|ContentChild
     */
    public function addContentChild($request, ContentChild $contentChild){
        try{
            $contentChild->content_parent_id = $request->content_parent_id;
            $contentChild->content_child_id = $request->content_child_id;
            $contentChild->user_id = $request->user_id;
            $contentChild->save();
            return $contentChild;
        }catch(QueryException $e){
            report($e);
            return false;
        }
    }

    /**
     * @param $id
     * @param ContentChild $contentChild
     * @return mixed
     */
    public function getOne($id, ContentChild $contentChild){
        return $contentChild->findOrFail($id);
    }

    /**
     * @param $id
     * @param ContentChild $contentChild
     * @return mixed
     */
    public function getByContentParentId($id, ContentChild $contentChild){
        return $contentChild->where('content_parent_id', $id)->get();
    }

    /**
     * @param $id
     * @param ContentChild $contentChild
     * @return mixed
     */
    public function deleteContentChild($id, ContentChild $contentChild){
        $contentChild = $this->getOne($id, $contentChild);
        if($contentChild!=null){
            return $contentChild->delete();
        }
    }

    /**
     * @param $id
     * @param ContentChild $contentChild
     * @return bool
     */
    public function deleteContentChildByContentId($id, ContentChild $contentChild){
        DB::beginTransaction();
        $result = true;
        $parentContent = $this->getByContentParentId($id, $contentChild);
        for ($i=0; $i < count($parentContent); $i++) { 
            $result = $parentContent[$i]->delete();
            if($result == false){
                $result = false;
            }
        }
        if($result){
            DB::commit();
            return true;
        }else{
            DB::rollBack();
            return false;
        }
    }

    /**
     * @param ContentChild $contentChild
     * @return mixed
     */
    public function getAll(ContentChild $contentChild){
        return $contentChild->get();
    }
}