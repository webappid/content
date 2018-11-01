<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\QueryException;
use WebAppId\Content\Models\Language;

/**
 * Class LanguageRepository
 * @package WebAppId\Content\Repositories
 */

class LanguageRepository
{

    /**
     * @param $request
     * @param Language $language
     * @return bool|Language
     */
    public function addLanguage($request, Language $language)
    {
        try {
            $language->code = $request->code;
            $language->name = $request->name;
            $language->image_id = $request->image_id;
            $language->user_id = $request->user_id;
            $language->save();

            return $language;
        } catch (QueryException $e) {
            report($e);
            return false;
        }
    }

    /**
     * @param Language $language
     * @return mixed
     */
    public function getLanguage(Language $language)
    {
        return $language->get();
    }

    /**
     * @param $name
     * @param Language $language
     * @return mixed
     */
    public function getLanguageByName($name, Language $language)
    {
        return $language->where('name', $name)->first();
    }
}