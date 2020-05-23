<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\Content;
use WebAppId\Content\Repositories\Requests\ContentRepositoryRequest;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 10:32:46
 * Time: 2020/04/22
 * Class ContentRepositoryContract
 * @package App\Repositories\Contracts
 */
interface ContentRepositoryContract
{
    /**
     * @param ContentRepositoryRequest $dummyRepositoryClassRequest
     * @param Content $content
     * @return Content|null
     */
    public function store(ContentRepositoryRequest $dummyRepositoryClassRequest, Content $content): ?Content;

    /**
     * @param string $code
     * @param ContentRepositoryRequest $dummyRepositoryClassRequest
     * @param Content $content
     * @return Content|null
     */
    public function update(string $code, ContentRepositoryRequest $dummyRepositoryClassRequest, Content $content): ?Content;

    /**
     * @param int $id
     * @param Content $content
     * @return Content|null
     */
    public function getById(int $id, Content $content): ?Content;

    /**
     * @param string $code
     * @param Content $content
     * @return Content|null
     */
    public function getByCode(string $code, Content $content): ?Content;

    /**
     * @param string $code
     * @param Content $content
     * @return bool
     */
    public function delete(string $code, Content $content): bool;

    /**
     * @param Content $content
     * @param int $length
     * @param int|null $category_id
     * @param array $categories
     * @param string|null $q
     * @return LengthAwarePaginator
     */
    public function get(Content $content,
                        int $length = 12,
                        int $category_id = null,
                        array $categories = [],
                        string $q = null): LengthAwarePaginator;

    /**
     * @param Content $content
     * @param int|null $category_id
     * @param array $categories
     * @param string|null $q
     * @return int
     */
    public function getCount(Content $content,
                             int $category_id = null,
                             array $categories = [],
                             string $q = null): int;

    /**
     * @param string $code
     * @param int $status_id
     * @param Content $content
     * @return Content|null
     */
    public function updateStatusByCode(string $code, int $status_id, Content $content): ?Content;

    /**
     * @param Content $content
     * @param string|null $q
     * @param int|null $id
     * @return int
     */
    public function getDuplicateTitle(Content $content, string $q = null, int $id = null): int;
}
