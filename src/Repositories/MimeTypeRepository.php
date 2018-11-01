<?php


/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\QueryException;
use WebAppId\Content\Models\MimeType;

/**
 * Class MimeTypeRepository
 * @package WebAppId\Content\Repositories
 */

class MimeTypeRepository
{
    /**
     * @param $request
     * @param MimeType $mimeType
     * @return bool|MimeType
     */
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

    /**
     * @param $id
     * @param MimeType $mimeType
     * @return mixed
     */
    public function getOne($id, MimeType $mimeType)
    {
        return $mimeType->findOrFail($id);
    }

    /**
     * Get Mime Type By Name
     *
     * @param String $name
     * @param MimeType $mimeType
     * @return Object $mimeType
     */
    public function getMimeByName($name, MimeType $mimeType)
    {
        return $mimeType->where('name', $name)->get();
    }
}