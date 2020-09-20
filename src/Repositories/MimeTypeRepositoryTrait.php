<?php
/**
 * Created by PhpStorm.
 */

namespace WebAppId\Content\Repositories;


use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\MimeType;
use WebAppId\Content\Repositories\Requests\MimeTypeRepositoryRequest;
use WebAppId\DDD\Tools\Lazy;
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
     * @inheritDoc
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
        $mimeType = $mimeType->find($id);
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