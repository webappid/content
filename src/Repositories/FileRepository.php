<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\QueryException;
use WebAppId\Content\Models\File;

/**
 * Class FileRepository
 * @package WebAppId\Content\Repositories
 */

class FileRepository
{
    /**
     * @param $request
     * @param File $file
     * @return bool|File
     */
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

    /**
     * @param $id
     * @param File $file
     * @return mixed
     */
    public function getOne($id, File $file){
        return $file->findOrFail($id);
    }

    /**
     * @param $name
     * @param File $file
     * @return mixed
     */
    public function getFileByName($name, File $file){
        return $file->where('name', $name)->first();
    }

    /**
     * @param File $file
     * @return mixed
     */
    public function getFileCount(File $file){
        return $file->count();
    }
}