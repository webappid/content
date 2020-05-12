<?php

/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\Language;
use WebAppId\Content\Repositories\Contracts\LanguageRepositoryContract;
use WebAppId\Content\Repositories\Requests\LanguageRepositoryRequest;
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

    /**
     * @param Language $language
     * @return Builder
     */
    protected function getJoin(Language $language): Builder
    {
        return $language
            ->join('files as files', 'languages.image_id', 'files.id')
            ->join('users as users', 'languages.user_id', 'users.id');
    }

    /**
     * @return array|string[]
     */
    protected function getColumn(): array
    {
        return [
            'languages.id',
            'languages.code',
            'languages.name',
            'languages.image_id',
            'languages.user_id',
            'files.name AS file_name',
            'files.description',
            'files.alt',
            'files.path',
            'users.name AS user_name'
        ];
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
        return $this->getJoin($language)->find($id, $this->getColumn());
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
    public function get(Language $language, int $length = 12, string $q = null): LengthAwarePaginator
    {
        return $this
            ->getJoin($language)
            ->when($q != null, function ($query) use ($q) {
                return $query->where('languages.name', 'LIKE', '%' . $q . '%');
            })
            ->paginate($length, $this->getColumn());
    }

    /**
     * @inheritDoc
     */
    public function getCount(Language $language, string $q = null): int
    {
        return $language
            ->when($q != null, function ($query) use ($q) {
                return $query->where('languages.name', 'LIKE', '%' . $q . '%');
            })
            ->count();
    }

    /**
     * @inheritDoc
     */
    public function getByName($name, Language $language): ?Language
    {
        return $language->where('languages.name', $name)->first();
    }
}
