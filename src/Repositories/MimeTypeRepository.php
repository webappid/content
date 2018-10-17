<?php

namespace WebAppId\Content\Repositories;

use WebAppId\Content\Models\MimeType;

class MimeTypeRepository
{
    private $mimeType;

    public function __construct(MimeType $mimeType)
    {
        $this->mimeType = $mimeType;
    }

    public function addMimeType($request)
    {
        try{
            $this->mimeType->name    = $request->name;
            $this->mimeType->user_id = $request->user_id;
            $this->mimeType->save();

            return $this->mimeType;
        }catch(QueryException $e){
            report($e);
            return false;
        }
    }

    public function getOne($id)
    {
        return $this->mimeType->findOrFail($id);
    }

    /**
     * Get Mime Type By Name
     *
     * @param String $name
     * @return Object $mimeType
     */
    public function getMimeByName($name)
    {
        return $this->mimeType->where('name', $name)->get();
    }
}