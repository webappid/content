<?php

namespace WebAppId\Content\Repositories;

use WebAppId\Content\Models\ContentChild;

class ContentChildRepository
{
    private $contentChild;

    public function __construct(ContentChild $contentChild)
    {
        $this->contentChild = $contentChild;
    }

    public function addContentChild($request){
        try{
            $this->contentChild->content_parent_id = $request->content_parent_id;
            $this->contentChild->content_child_id = $request->content_child_id;
            $this->contentChild->user_id = $request->user_id;
            $this->contentChild->save();
            return $this->contentChild;
        }catch(QueryException $e){
            report($e);
            return false;
        }
    }

    public function getOne($id){
        return $this->contentChild->findOrFail($id);
    }

    public function getByContentParentId($id){
        return $this->contentChild->where('content_parent_id', $id)->get();
    }

    public function deleteContentChild($id){
        $contentChild = $this->contentChild->getOne($id);
        if($contentChild!=null){
            return $contentChild->delete();
        }
    }

    public function deleteContentChildByContentId($id){
        DB::beginTransaction();
        $result = true;
        $parentContent = $this->contentChild->getByContentParentId($id);
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

    public function getAll(){
        return $this->contentChild->get();
    }
}