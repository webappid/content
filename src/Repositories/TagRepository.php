<?php

namespace WebAppId\Content\Repositories;

use WebAppId\Content\Models\Tag;

class TagRepository
{
    private $tag;

    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }

    public function addTag($request){
        try{
            $this->tag->name = $request->name;
            $this->tag->user_id = $request->user_id;
            $this->tag->save();
            return $this->tag;
        }catch(QueryException $e){
            report($e);
            return false;
        }
    }

    public function getTagByName($name){
        return $this->tag->where('name', $name)->first();
    }
}