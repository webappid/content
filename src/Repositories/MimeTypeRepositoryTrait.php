<?php
/**
 * Created by PhpStorm.
 */

namespace WebAppId\Content\Repositories;


use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\MimeType;
use WebAppId\Content\Repositories\Requests\MimeTypeRepositoryRequest;
use WebAppId\Lazy\Tools\Lazy;
use WebAppId\Lazy\Traits\RepositoryTrait;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 20/09/2020
 * Time: 22.20
 * Class MimeTypeRepositoryTrait
 * @package WebAppId\Content\Repositories
 */
trait MimeTypeRepositoryTrait
{
    use RepositoryTrait;

    /**
     * @param MimeTypeRepositoryRequest $mimeTypeRepositoryRequest
     * @param MimeType $mimeType
     * @return MimeType|null
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
     * @param int $id
     * @param MimeTypeRepositoryRequest $mimeTypeRepositoryRequest
     * @param MimeType $mimeType
     * @return MimeType|null
     */
    public function update(int $id, MimeTypeRepositoryRequest $mimeTypeRepositoryRequest, MimeType $mimeType): ?MimeType
    {
        $mimeType = $mimeType->find($id);
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
     * @param int $id
     * @param MimeType $mimeType
     * @return MimeType|null
     */
    public function getById(int $id, MimeType $mimeType): ?MimeType
    {
        return $this->getJoin($mimeType)->find($id, $this->getColumn());
    }

    /**
     * @param int $id
     * @param MimeType $mimeType
     * @return bool
     */
    public function delete(int $id, MimeType $mimeType): bool
    {
        $mimeType = $mimeType->find($id);
        if ($mimeType != null) {
            return $mimeType->delete();
        } else {
            return false;
        }
    }

    /**
     * @param MimeType $mimeType
     * @param int $length
     * @param string|null $q
     * @return LengthAwarePaginator
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
     * @param MimeType $mimeType
     * @param string|null $q
     * @return int
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
     * @param string $name
     * @param MimeType $mimeType
     * @return MimeType|null
     */
    public function getByName(string $name, MimeType $mimeType): ?MimeType
    {
        return $this->getJoin($mimeType)
            ->where('mime_types.name', $name)
            ->first($this->getColumn());
    }
}