<?php

namespace WebAppId\Content\Repositories;

use WebAppId\Content\Models\ContentStatus;

class ContentStatusRepository
{

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

    public function getContentStatus(ContentStatus $contentStatus)
    {
        return $contentStatus->get();
    }

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

    public function getContentStatusById($id, ContentStatus $contentStatus)
    {
        return $contentStatus->find($id);
    }

    public function getContentStatusesByName($name, ContentStatus $contentStatus){
        return $contentStatus->where('name', $name)->get();
    }
}