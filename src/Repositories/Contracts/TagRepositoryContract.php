<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\Tag;
use WebAppId\Content\Repositories\Requests\TagRepositoryRequest;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 05:01:58
 * Time: 2020/04/22
 * Class TagRepositoryContract
 * @package App\Repositories\Contracts
 */
interface TagRepositoryContract
{
    /**
     * @param TagRepositoryRequest $dummyRepositoryClassRequest
     * @param Tag $tag
     * @return Tag|null
     */
    public function store(TagRepositoryRequest $dummyRepositoryClassRequest, Tag $tag): ?Tag;

    /**
     * @param int $id
     * @param TagRepositoryRequest $dummyRepositoryClassRequest
     * @param Tag $tag
     * @return Tag|null
     */
    public function update(int $id, TagRepositoryRequest $dummyRepositoryClassRequest, Tag $tag): ?Tag;

    /**
     * @param int $id
     * @param Tag $tag
     * @return Tag|null
     */
    public function getById(int $id, Tag $tag): ?Tag;

    /**
     * @param int $id
     * @param Tag $tag
     * @return bool
     */
    public function delete(int $id, Tag $tag): bool;

    /**
     * @param Tag $tag
     * @param int $length
     * @return LengthAwarePaginator
     */
    public function get(Tag $tag, int $length = 12): LengthAwarePaginator;

    /**
     * @param Tag $tag
     * @return int
     */
    public function getCount(Tag $tag): int;

    /**
     * @param string $name
     * @param Tag $tag
     * @return Tag|null
     */
    public function getByName(string $name, Tag $tag): ?Tag;
}
