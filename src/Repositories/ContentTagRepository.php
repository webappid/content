<?php

namespace WebAppId\Content\Repositories;

use WebAppId\Content\Models\ContentTag;

class ContentTagRepository
{

    private $contentTag;

    public function __construct(ContentTag $contentTag)
    {
        $this->contentTag = $contentTag;
    }

    public function addContentTag($response){
        try{
            $this->contentTag->content_id = $response->content_id;
            $this->contentTag->tag_id = $response->tag_id;
            $this->contentTag->user_id = $response->user_id;
            return $this->contentTag->save();
        }catch(QueryException $e){
            dd($e);
            report($e);
            return false;
        }
    }

    public function deleteContentTagByContentId($contentId){
        $resultContentTag = $this->contentTag->where('content_id', $contentId)->get();
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