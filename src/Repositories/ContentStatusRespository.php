<?php

namespace WebAppId\Content\Repositories;

use WebAppId\Content\Models\ContentStatus;

class ContentStatusRepository
{
    private $contentStatus;

    public function __construct(ContentStatus $contentStatus)
    {
        $this->contentStatus = $contentStatus;   
    }

    public function addContentStatus($request)
    {
        try {
            $this->contentStatus->name = $request->name;
            $this->contentStatus->user_id = $request->user_id;
            $this->contentStatus->save();
            return $this->contentStatus;
        } catch (QueryException $e) {
            report($e);
            return false;
        }

    }

    public function getContentStatus()
    {
        return $this->contentStatus->get();
    }

    public function updateContentStatus($id, $request)
    {
        try {
            $result = $this->contentStatus->find($id);
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

    public function getContentStatusById($id)
    {
        return $this->contentStatus->find($id);
    }

    public function getContentStatusesByName($name){
        return $this->contentStatus->where('name', $name)->get();
    }
}