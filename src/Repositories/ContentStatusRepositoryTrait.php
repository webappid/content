<?php
/**
 * Created by PhpStorm.
 */

namespace WebAppId\Content\Repositories;


use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\ContentStatus;
use WebAppId\Content\Repositories\Requests\ContentStatusRepositoryRequest;
use WebAppId\DDD\Tools\Lazy;
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
     * @inheritDoc
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
     * @inheritDoc
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
     * @inheritDoc
     */
    public function getById(int $id, ContentStatus $contentStatus): ?ContentStatus
    {
        return $this->getJoin($contentStatus)->find($id, $this->getColumn());
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
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
     * @inheritDoc
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