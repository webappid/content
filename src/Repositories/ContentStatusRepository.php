<?php

/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\ContentStatus;
use WebAppId\Content\Repositories\Contracts\ContentStatusRepositoryContract;
use WebAppId\Content\Repositories\Requests\ContentStatusRepositoryRequest;
use WebAppId\Content\Services\Params\AddContentStatusParam;
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

    protected function getColumn($content)
    {
        return $content
            ->select
            (
                'content_statuses.id',
                'content_statuses.name'
            );
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
        return $this->getColumn($contentStatus)->find($id);
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
    public function get(ContentStatus $contentStatus, int $length = 12): LengthAwarePaginator
    {
        return $this->getColumn($contentStatus)->paginate($length);
    }

    /**
     * @inheritDoc
     */
    public function getCount(ContentStatus $contentStatus): int
    {
        return $contentStatus->count();
    }

    private function getQueryWhere(string $q, ContentStatus $contentStatus)
    {
        return $this->getColumn($contentStatus)
            ->where('name', 'LIKE', '%' . $q . '%');
    }

    /**
     * @inheritDoc
     */
    public function getWhere(string $q, ContentStatus $contentStatus, int $length = 12): LengthAwarePaginator
    {
        return $this
            ->getQueryWhere($q, $contentStatus)
            ->paginate($length);
    }

    /**
     * @inheritDoc
     */
    public function getWhereCount(string $q, ContentStatus $contentStatus, int $length = 12): int
    {
        return $this
            ->getQueryWhere($q, $contentStatus)
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
            ->first();
    }

    /**
     * @param AddContentStatusParam $addContentStatusParam
     * @param ContentStatus $contentStatus
     * @return ContentStatus|null
     * @deprecated
     */
    public function addContentStatus(AddContentStatusParam $addContentStatusParam,
                                     ContentStatus $contentStatus): ?ContentStatus
    {
        try {
            $contentStatus->name = $addContentStatusParam->getName();
            $contentStatus->user_id = $addContentStatusParam->getUserId();
            $contentStatus->save();
            return $contentStatus;
        } catch (QueryException $e) {
            report($e);
            return null;
        }

    }

    /**
     * @param ContentStatus $contentStatus
     * @return Collection
     * @deprecated
     */
    public function getContentStatus(ContentStatus $contentStatus): Collection
    {
        return $contentStatus->get();
    }

    /**
     * @param int $id
     * @param AddContentStatusParam $addContentStatusParam
     * @param ContentStatus $contentStatus
     * @return ContentStatus|null
     * @deprecated
     */
    public function updateContentStatus(int $id,
                                        AddContentStatusParam $addContentStatusParam,
                                        ContentStatus $contentStatus): ?ContentStatus
    {
        try {
            $result = $contentStatus->find($id);
            if (!empty($result)) {
                $result->name = $addContentStatusParam->getName();
                $result->user_id = $addContentStatusParam->getUserId();
                $result->save();
                return $result;
            } else {
                return null;
            }
        } catch (QueryException $e) {
            report($e);
            return null;
        }
    }

    /**
     * @param $id
     * @param ContentStatus $contentStatus
     * @return ContentStatus|null
     * @deprecated
     */
    public function getContentStatusById(int $id,
                                         ContentStatus $contentStatus): ?ContentStatus
    {
        return $contentStatus->find($id);
    }
}
