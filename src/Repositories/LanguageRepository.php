<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use WebAppId\Content\Models\Language;
use WebAppId\Content\Services\Params\AddLanguageParam;

/**
 * Class LanguageRepository
 * @package WebAppId\Content\Repositories
 */
class LanguageRepository
{

    /**
     * @param AddLanguageParam $addLanguageParam
     * @param Language $language
     * @return Language|null
     */
    public function addLanguage(AddLanguageParam $addLanguageParam, Language $language): ?Language
    {
        try {
            $language->code = $addLanguageParam->getCode();
            $language->name = $addLanguageParam->getName();
            $language->image_id = $addLanguageParam->getImageId();
            $language->user_id = $addLanguageParam->getUserId();
            $language->save();

            return $language;
        } catch (QueryException $e) {
            report($e);
            return null;
        }
    }

    /**
     * @param Language $language
     * @return Collection
     */
    public function getLanguage(Language $language): Collection
    {
        return $language->get();
    }

    /**
     * @param $name
     * @param Language $language
     * @return Language|null
     */
    public function getLanguageByName($name, Language $language): ?Language
    {
        return $language->where('name', $name)->first();
    }
}