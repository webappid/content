<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\MimeType;
use WebAppId\Content\Repositories\Requests\MimeTypeRepositoryRequest;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 04:25:12
 * Time: 2020/04/22
 * Class MimeTypeRepositoryContract
 * @package WebAppId\Content\Repositories\Contracts
 */
interface MimeTypeRepositoryContract
{
    /**
     * @param MimeTypeRepositoryRequest $dummyRepositoryClassRequest
     * @param MimeType $mimeType
     * @return MimeType|null
     */
    public function store(MimeTypeRepositoryRequest $dummyRepositoryClassRequest, MimeType $mimeType): ?MimeType;

    /**
     * @param int $id
     * @param MimeTypeRepositoryRequest $dummyRepositoryClassRequest
     * @param MimeType $mimeType
     * @return MimeType|null
     */
    public function update(int $id, MimeTypeRepositoryRequest $dummyRepositoryClassRequest, MimeType $mimeType): ?MimeType;

    /**
     * @param int $id
     * @param MimeType $mimeType
     * @return MimeType|null
     */
    public function getById(int $id, MimeType $mimeType): ?MimeType;

    /**
     * @param int $id
     * @param MimeType $mimeType
     * @return bool
     */
    public function delete(int $id, MimeType $mimeType): bool;

    /**
     * @param MimeType $mimeType
     * @param int $length
     * @return LengthAwarePaginator
     */
    public function get(MimeType $mimeType, int $length = 12): LengthAwarePaginator;

    /**
     * @param MimeType $mimeType
     * @return int
     */
    public function getCount(MimeType $mimeType): int;

    /**
     * @param string $q
     * @param MimeType $mimeType
     * @param int $length
     * @return LengthAwarePaginator
     */
    public function getWhere(string $q, MimeType $mimeType, int $length = 12): LengthAwarePaginator;

    /**
     * @param string $q
     * @param MimeType $mimeType
     * @param int $length
     * @return int
     */
    public function getWhereCount(string $q, MimeType $mimeType, int $length = 12): int;

    /**
     * @param string $name
     * @param MimeType $mimeType
     * @return MimeType|null
     */
    public function getByName(string $name, MimeType $mimeType): ?MimeType;
}
