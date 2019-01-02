<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use WebAppId\Content\Models\ContentTag;

/**
 * Class ContentTagRepository
 * @package WebAppId\Content\Repositories
 */

class ContentTagRepository
{

    /**
     * @param $response
     * @param ContentTag $contentTag
     * @return bool|ContentTag
     */
    public function addContentTag($response, ContentTag $contentTag)
    {
        try {
            $contentTag->content_id = $response->content_id;
            $contentTag->tag_id = $response->tag_id;
            $contentTag->user_id = $response->user_id;
            $contentTag->save();
            return $contentTag;
        } catch (QueryException $e) {
            dd($e);
            report($e);
            return false;
        }
    }

    /**
     * @param $contentId
     * @param ContentTag $contentTag
     */
    public function deleteContentTagByContentId($contentId, ContentTag $contentTag)
    {
        $resultContentTag = $contentTag->where('content_id', $contentId)->get();
        DB::beginTransaction();
        $result = true;
        if (count($resultContentTag) > 0) {
            for ($i = 0; $i < count($resultContentTag); $i++) {
                if (!$resultContentTag[$i]->delete()) {
                    $result = false;
                    break;
                }
            }
        }
        if ($result) {
            DB::commit();
        } else {
            DB::rollBack();
        }
    }
}