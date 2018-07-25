<?php

namespace WebAppId\Content\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class ContentChild extends Model
{
    protected $table='content_childs';

    protected $hidden=['user_id','created_at','updated_at'];

    protected $fillable=['id','content_parent_id','content_child_id'];

    public function addContentChild($request){
        try{
            $self = new self();
            $self->content_parent_id = $request->content_parent_id;
            $self->content_child_id = $request->content_child_id;
            $self->user_id = $request->user_id;
            $self->save();
            return $self;
        }catch(QueryException $e){
            report($e);
            return false;
        }
    }

    public function getOne($id){
        return $this->findOrFail($id);
    }

    public function getByContentParentId($id){
        return $this->where('content_parent_id', $id)->get();
    }

    public function deleteContentChild($id){
        $contentChild = $this->getOne($id);
        if($contentChild!=null){
            return $contentChild->delete();
        }
    }

    public function deleteContentChildByContentId($id){
        DB::beginTransaction();
        $result = true;
        $parentContent = $this->getByContentParentId($id);
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
        return $this->get();
    }
}