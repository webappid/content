<?php

namespace WebAppId\Content\Repositories;

use WebAppId\Content\Models\ContentGallery;

class ContentGalleryRepository
{

    private $contentGallery;

    public function __construct(ContentGallery $contentGallery)
    {
        $this->contentGallery = $contentGallery;
    }

    public function addContentGallery($request){
        try{
            $this->contentGallery->content_id = $request->content_id;
            $this->contentGallery->file_id = $request->file_id;
            $this->contentGallery->user_id = $request->user_id;
            $this->contentGallery->description = $request->description;
            $this->contentGallery->save();
            return $this->contentGallery;
        }catch(QueryException $e){
            report($e);
            return false;
        }
    }

    public function getContentGalleryByContentId($content_id){
        return $this->contentGallery->where('content_id', $content_id)->get();
    }

    public function deleteContentGalleryByContentId($content_id){
        try{
            $contentGalleries = $this->contentGallery->getContentGalleryByContentId($content_id);
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