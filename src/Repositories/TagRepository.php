<?php

namespace WebAppId\Content\Repositories;

use WebAppId\Content\Models\Tag;

class TagRepository
{
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

    public function getTagByName($name, Tag $tag){
        return $tag->where('name', $name)->first();
    }
}