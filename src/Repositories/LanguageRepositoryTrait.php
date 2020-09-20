<?php
/**
 * Created by PhpStorm.
 */

namespace WebAppId\Content\Repositories;


use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\Language;
use WebAppId\Content\Repositories\Requests\LanguageRepositoryRequest;
use WebAppId\DDD\Tools\Lazy;
use WebAppId\Lazy\Traits\RepositoryTrait;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 20/09/2020
 * Time: 22.16
 * Class LanguageRepositoryTrait
 * @package WebAppId\Content\Repositories
 */
trait LanguageRepositoryTrait
{
    use RepositoryTrait;

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
     * @inheritDoc
     */
    public function update(int $id, LanguageRepositoryRequest $languageRepositoryRequest, Language $language): ?Language
    {
        $language = $language->find($id);
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
        $language = $language->find($id);
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