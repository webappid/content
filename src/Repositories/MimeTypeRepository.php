<?php


/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\QueryException;
use WebAppId\Content\Models\MimeType;
use WebAppId\Content\Services\Params\AddMimeTypeParam;

/**
 * Class MimeTypeRepository
 * @package WebAppId\Content\Repositories
 */
class MimeTypeRepository
{
    /**
     * @param AddMimeTypeParam $addMimeTypeParam
     * @param MimeType $mimeType
     * @return MimeType|null
     */
    public function addMimeType(AddMimeTypeParam $addMimeTypeParam, MimeType $mimeType): ?MimeType
    {
        try {
            $mimeType->name = $addMimeTypeParam->getName();
            $mimeType->user_id = $addMimeTypeParam->getUserId();
            $mimeType->save();
        
            return $mimeType;
        } catch (QueryException $e) {
            report($e);
            return null;
        }
    }
    
    /**
     * @param $id
     * @param MimeType $mimeType
     * @return mixed
     */
    public function getOne(int $id, MimeType $mimeType): ?MimeType
    {
        return $mimeType->findOrFail($id);
    }
    
    /**
     * Get Mime Type By Name
     *
     * @param String $name
     * @param MimeType $mimeType
     * @return MimeType|null $mimeType
     */
    public function getMimeByName(string $name, MimeType $mimeType): ?MimeType
    {
        return $mimeType->where('name', $name)->first();
    }
}