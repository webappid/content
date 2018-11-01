<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\QueryException;
use WebAppId\Content\Models\Tag;

/**
 * Class TagRepository
 * @package WebAppId\Content\Repositories
 */

class TagRepository
{
    /**
     * @param $request
     * @param Tag $tag
     * @return bool|Tag
     */
    public function addTag($request, Tag $tag){
        try{
            $tag->name = $request->name;
            $tag->user_id = $request->user_id;
            $tag->save();
            return $tag;
        }catch(QueryException $e){
            report($e);
            return false;
        }
    }

    /**
     * @param $name
     * @param Tag $tag
     * @return mixed
     */
    public function getTagByName($name, Tag $tag){
        return $tag->where('name', $name)->first();
    }
}