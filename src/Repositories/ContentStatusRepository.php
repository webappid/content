<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\QueryException;
use WebAppId\Content\Models\ContentStatus;
use WebAppId\Content\Services\Params\AddContentStatusParam;

/**
 * Class ContentStatusRepository
 * @package WebAppId\Content\Repositories
 */
class ContentStatusRepository
{
    
    /**
     * @param AddContentStatusParam $addContentStatusParam
     * @param ContentStatus $contentStatus
     * @return ContentStatus|null
     */
    public function addContentStatus(AddContentStatusParam $addContentStatusParam,
                                     ContentStatus $contentStatus): ?ContentStatus
    {
        try {
            $contentStatus->name = $addContentStatusParam->getName();
            $contentStatus->user_id = $addContentStatusParam->getUserId();
            $contentStatus->save();
            return $contentStatus;
        } catch (QueryException $e) {
            report($e);
            return null;
        }
    
    }
    
    /**
     * @param ContentStatus $contentStatus
     * @return object|null
     */
    public function getContentStatus(ContentStatus $contentStatus): ?object
    {
        return $contentStatus->get();
    }
    
    /**
     * @param int $id
     * @param AddContentStatusParam $addContentStatusParam
     * @param ContentStatus $contentStatus
     * @return ContentStatus|null
     */
    public function updateContentStatus(int $id,
                                        AddContentStatusParam $addContentStatusParam,
                                        ContentStatus $contentStatus): ?ContentStatus
    {
        try {
            $result = $contentStatus->find($id);
            if (!empty($result)) {
                $result->name = $addContentStatusParam->getName();
                $result->user_id = $addContentStatusParam->getUserId();
                $result->save();
                return $result;
            } else {
                return null;
            }
        } catch (QueryException $e) {
            report($e);
            return null;
        }
    }
    
    /**
     * @param $id
     * @param ContentStatus $contentStatus
     * @return ContentStatus|null
     */
    public function getContentStatusById(int $id,
                                         ContentStatus $contentStatus): ?ContentStatus
    {
        return $contentStatus->find($id);
    }
    
    /**
     * @param $name
     * @param ContentStatus $contentStatus
     * @return object|null
     */
    public function getContentStatusesByName(string $name,
                                             ContentStatus $contentStatus): ?object
    {
        return $contentStatus->where('name', $name)->get();
    }
}