<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\QueryException;
use WebAppId\Content\Models\ContentStatus;

/**
 * Class ContentStatusRepository
 * @package WebAppId\Content\Repositories
 */

class ContentStatusRepository
{

    /**
     * @param $request
     * @param ContentStatus $contentStatus
     * @return bool|ContentStatus
     */
    public function addContentStatus($request, ContentStatus $contentStatus)
    {
        try {
            $contentStatus->name = $request->name;
            $contentStatus->user_id = $request->user_id;
            $contentStatus->save();
            return $contentStatus;
        } catch (QueryException $e) {
            report($e);
            return false;
        }

    }

    /**
     * @param ContentStatus $contentStatus
     * @return mixed
     */
    public function getContentStatus(ContentStatus $contentStatus)
    {
        return $contentStatus->get();
    }

    /**
     * @param $id
     * @param $request
     * @param ContentStatus $contentStatus
     * @return bool
     */
    public function updateContentStatus($id, $request, ContentStatus $contentStatus)
    {
        try {
            $result = $contentStatus->find($id);
            if (!empty($result)) {
                $result->name = $request->name;
                $result->user_id = $request->user_id;
                $result->save();
                return $result;
            } else {
                return false;
            }
        } catch (QueryException $e) {
            report($e);
            return false;
        }
    }

    /**
     * @param $id
     * @param ContentStatus $contentStatus
     * @return mixed
     */
    public function getContentStatusById($id, ContentStatus $contentStatus)
    {
        return $contentStatus->find($id);
    }

    /**
     * @param $name
     * @param ContentStatus $contentStatus
     * @return mixed
     */
    public function getContentStatusesByName($name, ContentStatus $contentStatus){
        return $contentStatus->where('name', $name)->get();
    }
}