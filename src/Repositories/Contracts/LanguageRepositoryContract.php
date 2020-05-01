<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\Language;
use WebAppId\Content\Repositories\Requests\LanguageRepositoryRequest;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 04:05:19
 * Time: 2020/04/22
 * Class LanguageRepositoryContract
 * @package WebAppId\Content\Repositories\Contracts
 */
interface LanguageRepositoryContract
{
    /**
     * @param LanguageRepositoryRequest $dummyRepositoryClassRequest
     * @param Language $language
     * @return Language|null
     */
    public function store(LanguageRepositoryRequest $dummyRepositoryClassRequest, Language $language): ?Language;

    /**
     * @param int $id
     * @param LanguageRepositoryRequest $dummyRepositoryClassRequest
     * @param Language $language
     * @return Language|null
     */
    public function update(int $id, LanguageRepositoryRequest $dummyRepositoryClassRequest, Language $language): ?Language;

    /**
     * @param int $id
     * @param Language $language
     * @return Language|null
     */
    public function getById(int $id, Language $language): ?Language;

    /**
     * @param int $id
     * @param Language $language
     * @return bool
     */
    public function delete(int $id, Language $language): bool;

    /**
     * @param Language $language
     * @param int $length
     * @param string|null $q
     * @return LengthAwarePaginator
     */
    public function get(Language $language, int $length = 12, string $q = null): LengthAwarePaginator;

    /**
     * @param Language $language
     * @param string|null $q
     * @return int
     */
    public function getCount(Language $language, string $q = null): int;

    /**
     * @param $name
     * @param Language $language
     * @return Language|null
     */
    public function getByName($name, Language $language): ?Language;
}
