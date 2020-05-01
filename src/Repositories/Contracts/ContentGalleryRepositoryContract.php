<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\ContentGallery;
use WebAppId\Content\Repositories\Requests\ContentGalleryRepositoryRequest;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 15:14:13
 * Time: 2020/04/23
 * Class ContentGalleryRepositoryContract
 * @package WebAppId\Content\Repositories\Contracts
 */
interface ContentGalleryRepositoryContract
{
    /**
     * @param ContentGalleryRepositoryRequest $dummyRepositoryClassRequest
     * @param ContentGallery $contentGallery
     * @return ContentGallery|null
     */
    public function store(ContentGalleryRepositoryRequest $dummyRepositoryClassRequest, ContentGallery $contentGallery): ?ContentGallery;

    /**
     * @param int $contentId
     * @param ContentGallery $contentGallery
     * @return Collection
     */
    public function getByContentId(int $contentId, ContentGallery $contentGallery): Collection;

    /**
     * @param int $contentId
     * @param ContentGallery $contentGallery
     * @return bool
     */
    public function deleteByContentId(int $contentId, ContentGallery $contentGallery): bool;

    /**
     * @param ContentGallery $contentGallery
     * @param int $length
     * @param string $q
     * @return LengthAwarePaginator
     */
    public function get(ContentGallery $contentGallery, int $length = 12, string $q = null): LengthAwarePaginator;

    /**
     * @param ContentGallery $contentGallery
     * @param string $q
     * @return int
     */
    public function getCount(ContentGallery $contentGallery, string $q = null): int;


}
