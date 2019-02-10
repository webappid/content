<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\QueryException;
use WebAppId\Content\Models\Tag;
use WebAppId\Content\Services\Params\AddTagParam;

/**
 * Class TagRepository
 * @package WebAppId\Content\Repositories
 */
class TagRepository
{
    /**
     * @param AddTagParam $addTagParam
     * @param Tag $tag
     * @return Tag|null
     */
    public function addTag(AddTagParam $addTagParam, Tag $tag): ?Tag
    {
        try {
            $tag->name = $addTagParam->getName();
            $tag->user_id = $addTagParam->getUserId();
            $tag->save();
            return $tag;
        } catch (QueryException $e) {
            report($e);
            return null;
        }
    }
    
    /**
     * @param $name
     * @param Tag $tag
     * @return Tag|null
     */
    public function getTagByName(string $name, Tag $tag): ?Tag
    {
        return $tag->where('name', $name)->first();
    }
}