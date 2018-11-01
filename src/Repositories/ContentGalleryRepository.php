<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\QueryException;
use WebAppId\Content\Models\ContentGallery;

/**
 * Class ContentGalleryRepository
 * @package WebAppId\Content\Repositories
 */

class ContentGalleryRepository
{

    /**
     * @param $request
     * @param ContentGallery $contentGallery
     * @return bool|ContentGallery
     */
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

    /**
     * @param $content_id
     * @param ContentGallery $contentGallery
     * @return mixed
     */
    public function getContentGalleryByContentId($content_id, ContentGallery $contentGallery){
        return $contentGallery->where('content_id', $content_id)->get();
    }

    /**
     * @param $content_id
     * @param ContentGallery $contentGallery
     * @return bool
     */
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