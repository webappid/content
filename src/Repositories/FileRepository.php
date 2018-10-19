<?php

namespace WebAppId\Content\Repositories;

use WebAppId\Content\Models\File;

class FileRepository
{
    public function addFile($request, File $file){
        try{
            $file->name         = $request->name;
            $file->description  = $request->description;
            $file->alt          = $request->alt;
            $file->path         = $request->path;
            $file->mime_type_id = $request->mime_type_id;
            $file->owner_id     = $request->owner_id;
            $file->user_id      = $request->user_id;
            $file->save();
            
            return $file;
        }catch(QueryException $e){
            report($e);
            return false;
        }
    }

    public function getOne($id, File $file){
        return $file->findOrFail($id);
    }

    public function getFileByName($name, File $file){
        return $file->where('name', $name)->first();
    }

    public function getFileCount(File $file){
        return $file->count();
    }
}