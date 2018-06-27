<?php

namespace WebAppId\Content\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class ContentTag extends Model
{
    protected $table='content_tags';

    protected $hidden=['user_id','created_at','updated_at'];

    protected $fillable=['id','content_id','tag_id'];

    public function addContentTag($response){
        try{
            $this->content_id = $response->content_id;
            $this->tag_id = $response->tag_id;
            $this->user_id = $response->user_id;
            return $this->save();
        }catch(QueryException $e){
            dd($e);
            report($e);
            return false;
        }
    }

    public function deleteContentTagByContentId($contentId){
        $resultContentTag = $this->where('content_id', $contentId)->get();
        DB::beginTransaction();
        $result = true;
        if(count($resultContentTag)>0){
            for ($i=0; $i < count($resultContentTag); $i++) { 
                if($resultContentTag[$i]->delete()==false){
                    $result=false;
                    break;
                }
            }
        }
        if($result){
            DB::commit();
        }else{
            DB::rollBack();
        }
    }
}