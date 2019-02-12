<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\QueryException;
use WebAppId\Content\Models\File;
use WebAppId\Content\Services\Params\AddFileParam;

/**
 * Class FileRepository
 * @package WebAppId\Content\Repositories
 */
class FileRepository
{
    /**
     * @param AddFileParam $addFileParam
     * @param File $file
     * @return File|null
     */
    public function addFile(AddFileParam $addFileParam, File $file): ?File
    {
        try {
            $file->name = $addFileParam->getName();
            $file->description = $addFileParam->getDescription();
            $file->alt = $addFileParam->getAlt();
            $file->path = $addFileParam->getPath();
            $file->mime_type_id = $addFileParam->getMimeTypeId();
            $file->owner_id = $addFileParam->getOwnerId();
            $file->user_id = $addFileParam->getUserId();
            $file->save();
            
            return $file;
        } catch (QueryException $e) {
            report($e);
            return null;
        }
    }
    
    /**
     * @param int $id
     * @param File $file
     * @return File|null
     */
    public function getOne(int $id, File $file): ?File
    {
        return $file->findOrFail($id);
    }
    
    /**
     * @param string $name
     * @param File $file
     * @return File|null
     */
    public function getFileByName(string $name, File $file): ?File
    {
        return $file->where('name', $name)->first();
    }
    
    /**
     * @param File $file
     * @return int
     */
    public function getFileCount(File $file): int
    {
        return $file->count();
    }
}