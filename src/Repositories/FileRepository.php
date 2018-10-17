<?php

namespace WebAppId\Content\Repositories;

use WebAppId\Content\Models\File;

class FileRepository
{
    private $file;

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    public function addFile($request){
        try{
            $this->file->name         = $request->name;
            $this->file->description  = $request->description;
            $this->file->alt          = $request->alt;
            $this->file->path         = $request->path;
            $this->file->mime_type_id = $request->mime_type_id;
            $this->file->owner_id     = $request->owner_id;
            $this->file->user_id      = $request->user_id;
            $this->file->save();
            
            return $this->file;
        }catch(QueryException $e){
            report($e);
            return false;
        }
    }

    public function getOne($id){
        return $this->file->findOrFail($id);
    }

    public function getFileByName($name){
        return $this->file->where('name',$name)->first();
    }
}