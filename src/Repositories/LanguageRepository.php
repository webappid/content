<?php

namespace WebAppId\Content\Repositories;

use WebAppId\Content\Models\Language;

class LanguageRepository
{
    private $language;

    public function __construct(Language $language)
    {
        $this->language = $language;
    }

    public function addLanguage($request)
    {
        try{
            $this->language->code     = $request->code;
            $this->language->name     = $request->name;
            $this->language->image_id = $request->image_id;
            $this->language->user_id  = $request->user_id;
            $this->language->save();

            return $this->language;
        }catch(QueryException $e){
            report($e);
            return false;
        }
    }

    public function getLanguage()
    {
        return $this->language->get();
    }

    public function getLanguageByName($name){
        return $this->language->where('name',$name)->first();
    }
}