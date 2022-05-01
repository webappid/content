<?php
/**
 * Created by PhpStorm.
 */

namespace WebAppId\Content\Repositories;


use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\ContentStatus;
use WebAppId\Content\Repositories\Requests\ContentStatusRepositoryRequest;
use WebAppId\Lazy\Tools\Lazy;
use WebAppId\Lazy\Traits\RepositoryTrait;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 20/09/2020
 * Time: 20.20
 * Class ContentStatusRepositoryTrait
 * @package WebAppId\Content\Repositories
 */
trait ContentStatusRepositoryTrait
{
    use RepositoryTrait;

    /**
     * @param ContentStatusRepositoryRequest $contentStatusRepositoryRequest
     * @param ContentStatus $contentStatus
     * @return ContentStatus|null
     */
    public function store(ContentStatusRepositoryRequest $contentStatusRepositoryRequest, ContentStatus $contentStatus): ?ContentStatus
    {
        try {
            $contentStatus = Lazy::copy($contentStatusRepositoryRequest, $contentStatus);
            $contentStatus->save();
            return $contentStatus;
        } catch (QueryException $queryException) {
            report($queryException);
            return null;
        }
    }

    /**
     * @param int $id
     * @param ContentStatusRepositoryRequest $contentStatusRepositoryRequest
     * @param ContentStatus $contentStatus
     * @return ContentStatus|null
     */
    public function update(int $id, ContentStatusRepositoryRequest $contentStatusRepositoryRequest, ContentStatus $contentStatus): ?ContentStatus
    {
        $contentStatus = $contentStatus->find($id);
        if ($contentStatus != null) {
            try {
                $contentStatus = Lazy::copy($contentStatusRepositoryRequest, $contentStatus);
                $contentStatus->save();
                return $contentStatus;
            } catch (QueryException $queryException) {
                report($queryException);
            }
        }
        return $contentStatus;
    }

    /**
     * @param int $id
     * @param ContentStatus $contentStatus
     * @return ContentStatus|null
     */
    public function getById(int $id, ContentStatus $contentStatus): ?ContentStatus
    {
        return $this->getJoin($contentStatus)->find($id, $this->getColumn());
    }

    /**
     * @param int $id
     * @param ContentStatus $contentStatus
     * @return bool
     */
    public function delete(int $id, ContentStatus $contentStatus): bool
    {
        $contentStatus = $contentStatus->find($id);
        if ($contentStatus != null) {
            return $contentStatus->delete();
        } else {
            return false;
        }
    }

    /**
     * @param ContentStatus $contentStatus
     * @param int $length
     * @param string|null $q
     * @return LengthAwarePaginator
     */
    public function get(ContentStatus $contentStatus, int $length = 12, string $q = null): LengthAwarePaginator
    {
        return $this->getJoin($contentStatus)
            ->when($q != null, function ($query) use ($q) {
                return $query->where('content_statuses.name', 'LIKE', '%' . $q . '%');
            })
            ->paginate($length, $this->getColumn());
    }

    /**
     * @param ContentStatus $contentStatus
     * @param string|null $q
     * @return int
     */
    public function getCount(ContentStatus $contentStatus, string $q = null): int
    {
        return $contentStatus
            ->when($q != null, function ($query) use ($q) {
                return $query->where('content_statuses.name', 'LIKE', '%' . $q . '%');
            })
            ->count();
    }

    /**
     * @param string $name
     * @param ContentStatus $contentStatus
     * @return ContentStatus
     */
    public function getByName(string $name,
                              ContentStatus $contentStatus): ?ContentStatus
    {
        return $this
            ->getJoin($contentStatus)
            ->where('content_statuses.name', $name)
            ->first($this->getColumn());
    }
}