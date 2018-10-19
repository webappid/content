<?php

namespace WebAppId\Content\Repositories;

use WebAppId\Content\Models\MimeType;

class MimeTypeRepository
{
    public function addMimeType($request, MimeType $mimeType)
    {
        try{
            $mimeType->name    = $request->name;
            $mimeType->user_id = $request->user_id;
            $mimeType->save();

            return $mimeType;
        }catch(QueryException $e){
            report($e);
            return false;
        }
    }

    public function getOne($id, MimeType $mimeType)
    {
        return $mimeType->findOrFail($id);
    }

    /**
     * Get Mime Type By Name
     *
     * @param String $name
     * @return Object $mimeType
     */
    public function getMimeByName($name, MimeType $mimeType)
    {
        return $mimeType->where('name', $name)->get();
    }
}