<?php

namespace WebAppId\Content\Repositories;

use WebAppId\Content\Models\ContentChild;

class ContentChildRepository
{

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

    public function getOne($id, ContentChild $contentChild){
        return $contentChild->findOrFail($id);
    }

    public function getByContentParentId($id, ContentChild $contentChild){
        return $contentChild->where('content_parent_id', $id)->get();
    }

    public function deleteContentChild($id, ContentChild $contentChild){
        $contentChild = $this->getOne($id, $contentChild);
        if($contentChild!=null){
            return $contentChild->delete();
        }
    }

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

    public function getAll(ContentChild $contentChild){
        return $contentChild->get();
    }
}