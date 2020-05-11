<?php

/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\ContentStatus;
use WebAppId\Content\Repositories\Contracts\ContentStatusRepositoryContract;
use WebAppId\Content\Repositories\Requests\ContentStatusRepositoryRequest;
use WebAppId\DDD\Tools\Lazy;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 22/04/20
 * Time: 04.49
 * Class ContentStatusRepository
 * @package WebAppId\Content\Repositories
 */
class ContentStatusRepository implements ContentStatusRepositoryContract
{
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
     * @param ContentStatus $contentStatus
     * @return Builder
     */
    protected function getJoin(ContentStatus $contentStatus): Builder
    {
        return $contentStatus;
    }

    /**
     * @return array|string[]
     */
    protected function getColumn(): array
    {
        return [
            'content_statuses.id',
            'content_statuses.name'
        ];

    }

    /**
     * @inheritDoc
     */
    public function update(int $id, ContentStatusRepositoryRequest $contentStatusRepositoryRequest, ContentStatus $contentStatus): ?ContentStatus
    {
        $contentStatus = $this->getById($id, $contentStatus);
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
        $contentStatus = $this->getById($id, $contentStatus);
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
        return $this
            ->getJoin($contentStatus)
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
        return $contentStatus->where('content_statuses.name', $name)
            ->first($this->getColumn());
    }
}
