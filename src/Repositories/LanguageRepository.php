<?php

namespace WebAppId\Content\Repositories;

use WebAppId\Content\Models\Language;

class LanguageRepository
{
    private $language;

    public function addLanguage($request, Language $language)
    {
        try{
            $language->code     = $request->code;
            $language->name     = $request->name;
            $language->image_id = $request->image_id;
            $language->user_id  = $request->user_id;
            $language->save();

            return $language;
        }catch(QueryException $e){
            report($e);
            return false;
        }
    }

    public function getLanguage(Language $language)
    {
        return $language->get();
    }

    public function getLanguageByName($name, Language $language){
        return $language->where('name',$name)->first();
    }
}