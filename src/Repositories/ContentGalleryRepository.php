<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\QueryException;
use WebAppId\Content\Models\ContentGallery;
use WebAppId\Content\Services\Params\AddContentGalleryParam;

/**
 * Class ContentGalleryRepository
 * @package WebAppId\Content\Repositories
 */
class ContentGalleryRepository
{
    
    /**
     * @param AddContentGalleryParam $addContentGalleryParam
     * @param ContentGallery $contentGallery
     * @return ContentGallery|null
     */
    public function addContentGallery(AddContentGalleryParam $addContentGalleryParam, ContentGallery $contentGallery): ?ContentGallery
    {
        try {
            $contentGallery->content_id = $addContentGalleryParam->getContentId();
            $contentGallery->file_id = $addContentGalleryParam->getFileId();
            $contentGallery->user_id = $addContentGalleryParam->getUserId();
            $contentGallery->description = $addContentGalleryParam->getDescription();
            $contentGallery->save();
            return $contentGallery;
        } catch (QueryException $e) {
            report($e);
            return null;
        }
    }
    
    /**
     * @param $content_id
     * @param ContentGallery $contentGallery
     * @return mixed
     */
    public function getContentGalleryByContentId(int $content_id, ContentGallery $contentGallery): ?object
    {
        return $contentGallery->where('content_id', $content_id)->get();
    }
    
    /**
     * @param $content_id
     * @param ContentGallery $contentGallery
     * @return bool
     */
    public function deleteContentGalleryByContentId(int $content_id, ContentGallery $contentGallery): bool
    {
        try {
            $contentGalleries = $this->getContentGalleryByContentId($content_id, $contentGallery);
            for ($i = 0; $i < count($contentGalleries); $i++) {
                $contentGalleries[0]->delete();
            }
            return true;
        } catch (QueryException $e) {
            report($e);
            return false;
        }
    }
}