<?php

namespace WebAppId\Content\Repositories;

use WebAppId\Content\Models\ContentGallery;

class ContentGalleryRepository
{

    public function addContentGallery($request, ContentGallery $contentGallery){
        try{
            $contentGallery->content_id = $request->content_id;
            $contentGallery->file_id = $request->file_id;
            $contentGallery->user_id = $request->user_id;
            $contentGallery->description = $request->description;
            $contentGallery->save();
            return $contentGallery;
        }catch(QueryException $e){
            report($e);
            return false;
        }
    }

    public function getContentGalleryByContentId($content_id, ContentGallery $contentGallery){
        return $contentGallery->where('content_id', $content_id)->get();
    }

    public function deleteContentGalleryByContentId($content_id, ContentGallery $contentGallery){
        try{
            $contentGalleries = $this->getContentGalleryByContentId($content_id, $contentGallery);
            for ($i=0; $i < count($contentGalleries); $i++) { 
                $contentGalleries[0]->delete();
            }
            return true;
        }catch(QueryException $e){
            report($e);
            return false;
        }
    }
}