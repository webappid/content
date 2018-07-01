<?php

namespace WebAppId\Content\Models;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Model; 

class ContentGallery extends Model
{
    protected $table='content_galleries';
    
    protected $fillable=['id','content_id','file_id','description'];

    protected $hidden = ['user_id','crated_at', 'updated_at'];


    public function addContentGallery($request){
        try{
            $result = new self();
            $result->content_id = $request->content_id;
            $result->file_id = $request->file_id;
            $result->user_id = $request->user_id;
            $result->description = $request->description;
            $result->save();
            return $result;
        }catch(QueryException $e){
            report($e);
            return false;
        }
    }

    public function getContentGalleryByContentId($content_id){
        return $this->where('content_id', $content_id)->get();
    }

    public function deleteContentGalleryByContentId($content_id){
        try{
            $contentGalleries = $this->getContentGalleryByContentId($content_id);
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
