<?php


/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\MimeType;
use WebAppId\Content\Repositories\Contracts\MimeTypeRepositoryContract;
use WebAppId\Content\Repositories\Requests\MimeTypeRepositoryRequest;
use WebAppId\DDD\Tools\Lazy;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 22/04/20
 * Time: 04.56
 * Class MimeTypeRepository
 * @package WebAppId\Content\Repositories
 */
class MimeTypeRepository implements MimeTypeRepositoryContract
{
    /**
     * @inheritDoc
     */
    public function store(MimeTypeRepositoryRequest $mimeTypeRepositoryRequest, MimeType $mimeType): ?MimeType
    {
        try {
            $mimeType = Lazy::copy($mimeTypeRepositoryRequest, $mimeType);
            $mimeType->save();
            return $mimeType;
        } catch (QueryException $queryException) {
            report($queryException);
            return null;
        }
    }

    /**
     * @param MimeType $mimeType
     * @return Builder
     */
    protected function getJoin(MimeType $mimeType): Builder
    {
        return $mimeType
            ->join('users as users', 'mime_types.user_id', 'users.id');
    }

    /**
     * @return array|string[]
     */
    protected function getColumn(): array
    {
        return [
            'mime_types.id',
            'mime_types.name',
            'users.id AS user_id',
            'users.name AS user_name'
        ];

    }

    /**
     * @inheritDoc
     */
    public function update(int $id, MimeTypeRepositoryRequest $mimeTypeRepositoryRequest, MimeType $mimeType): ?MimeType
    {
        $mimeType = $this->getById($id, $mimeType);
        if ($mimeType != null) {
            try {
                $mimeType = Lazy::copy($mimeTypeRepositoryRequest, $mimeType);
                $mimeType->save();
                return $mimeType;
            } catch (QueryException $queryException) {
                report($queryException);
            }
        }
        return $mimeType;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id, MimeType $mimeType): ?MimeType
    {
        return $this->getJoin($mimeType)->find($id, $this->getColumn());
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id, MimeType $mimeType): bool
    {
        $mimeType = $this->getById($id, $mimeType);
        if ($mimeType != null) {
            return $mimeType->delete();
        } else {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function get(MimeType $mimeType, int $length = 12, string $q = null): LengthAwarePaginator
    {
        return $this->getJoin($mimeType)
            ->when($q != null, function ($query) use ($q) {
                return $query->where('mime_types.name', 'LIKE', '%' . $q . '%');
            })
            ->orderBy('mime_types.name', 'asc')
            ->paginate($length, $this->getColumn());
    }

    /**
     * @inheritDoc
     */
    public function getCount(MimeType $mimeType, string $q = null): int
    {
        return $mimeType
            ->when($q != null, function ($query) use ($q) {
                return $query->where('mime_types.name', 'LIKE', '%' . $q . '%');
            })
            ->count();
    }

    /**
     * @inheritDoc
     */
    public function getByName(string $name, MimeType $mimeType): ?MimeType
    {
        return $this->getJoin($mimeType)
            ->where('mime_types.name', $name)
            ->first($this->getColumn());
    }
}
