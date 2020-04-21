<?php

/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\Language;
use WebAppId\Content\Repositories\Contracts\LanguageRepositoryContract;
use WebAppId\Content\Repositories\Requests\LanguageRepositoryRequest;
use WebAppId\Content\Services\Params\AddLanguageParam;
use WebAppId\DDD\Tools\Lazy;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 22/04/20
 * Time: 05.28
 * Class LanguageRepository
 * @package WebAppId\Content\Repositories
 */
class LanguageRepository implements LanguageRepositoryContract
{

    /**
     * @inheritDoc
     */
    public function store(LanguageRepositoryRequest $languageRepositoryRequest, Language $language): ?Language
    {
        try {
            $language = Lazy::copy($languageRepositoryRequest, $language);
            $language->save();
            return $language;
        } catch (QueryException $queryException) {
            report($queryException);
            return null;
        }
    }

    protected function getColumn($content)
    {
        return $content
            ->select
            (
                'languages.id',
                'languages.code',
                'languages.name',
                'languages.image_id',
                'languages.user_id',
                'files.id',
                'files.name',
                'files.description',
                'files.alt',
                'files.path',
                'files.mime_type_id',
                'users.id',
                'users.name'
            )
            ->join('files as files', 'languages.image_id', 'files.id')
            ->join('users as users', 'languages.user_id', 'users.id');
    }

    /**
     * @inheritDoc
     */
    public function update(int $id, LanguageRepositoryRequest $languageRepositoryRequest, Language $language): ?Language
    {
        $language = $this->getById($id, $language);
        if ($language != null) {
            try {
                $language = Lazy::copy($languageRepositoryRequest, $language);
                $language->save();
                return $language;
            } catch (QueryException $queryException) {
                report($queryException);
            }
        }
        return $language;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id, Language $language): ?Language
    {
        return $this->getColumn($language)->find($id);
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id, Language $language): bool
    {
        $language = $this->getById($id, $language);
        if ($language != null) {
            return $language->delete();
        } else {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function get(Language $language, int $length = 12): LengthAwarePaginator
    {
        return $this->getColumn($language)->paginate($length);
    }

    /**
     * @inheritDoc
     */
    public function getCount(Language $language): int
    {
        return $language->count();
    }

    private function getQueryWhere(string $q, Language $language)
    {
        return $this->getColumn($language)
            ->where('languages.code', 'LIKE', '%' . $q . '%');
    }

    /**
     * @inheritDoc
     */
    public function getWhere(string $q, Language $language, int $length = 12): LengthAwarePaginator
    {
        return $this
            ->getQueryWhere($q, $language)
            ->paginate($length);
    }

    /**
     * @inheritDoc
     */
    public function getWhereCount(string $q, Language $language, int $length = 12): int
    {
        return $this
            ->getQueryWhere($q, $language)
            ->count();
    }

    /**
     * @inheritDoc
     */
    public function getByName($name, Language $language): ?Language
    {
        return $language->where('languages.name', $name)->first();
    }

    /**
     * @param AddLanguageParam $addLanguageParam
     * @param Language $language
     * @return Language|null
     * @deprecated
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
     * @deprecated
     */
    public function getLanguage(Language $language): Collection
    {
        return $language->get();
    }


}
