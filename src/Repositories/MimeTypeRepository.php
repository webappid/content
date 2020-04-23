<?php


/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\MimeType;
use WebAppId\Content\Repositories\Contracts\MimeTypeRepositoryContract;
use WebAppId\Content\Repositories\Requests\MimeTypeRepositoryRequest;
use WebAppId\Content\Services\Params\AddMimeTypeParam;
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

    protected function getColumn($mimeType)
    {
        return $mimeType
            ->select
            (
                'mime_types.id',
                'mime_types.name',
                'users.id',
                'users.name'
            )
            ->join('users as users', 'mime_types.user_id', 'users.id');
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
        return $this->getColumn($mimeType)->find($id);
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
        return $this->getColumn($mimeType)
            ->when($q != null, function ($query) use ($q) {
                return $query->where('mime_types.name', 'LIKE', '%' . $q . '%');
            })
            ->orderBy('mime_types.name', 'asc')
            ->paginate($length);
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
        return $this->getColumn($mimeType)
            ->where('mime_types.name', $name)
            ->first();
    }

    /**
     * @param AddMimeTypeParam $addMimeTypeParam
     * @param MimeType $mimeType
     * @return MimeType|null
     * @deprecated
     */
    public function addMimeType(AddMimeTypeParam $addMimeTypeParam, MimeType $mimeType): ?MimeType
    {
        try {
            $mimeType->name = $addMimeTypeParam->getName();
            $mimeType->user_id = $addMimeTypeParam->getUserId();
            $mimeType->save();

            return $mimeType;
        } catch (QueryException $e) {
            report($e);
            return null;
        }
    }

    /**
     * @param $id
     * @param MimeType $mimeType
     * @return mixed
     * @deprecated
     */
    public function getOne(int $id, MimeType $mimeType): ?MimeType
    {
        return $mimeType->findOrFail($id);
    }

}
