<?php

/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\File;
use WebAppId\Content\Repositories\Contracts\FileRepositoryContract;
use WebAppId\Content\Repositories\Requests\FileRepositoryRequest;
use WebAppId\Content\Services\Params\AddFileParam;
use WebAppId\DDD\Tools\Lazy;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 22/04/20
 * Time: 04.13
 * Class FileRepository
 * @package WebAppId\Content\Repositories
 */
class FileRepository implements FileRepositoryContract
{
    /**
     * @inheritDoc
     */
    public function store(FileRepositoryRequest $fileRepositoryRequest, File $file): ?File
    {
        try {
            $file = Lazy::copy($fileRepositoryRequest, $file);
            $file->save();
            return $file;
        } catch (QueryException $queryException) {
            report($queryException);
            return null;
        }
    }

    /**
     * @param File $file
     * @return Builder
     */
    protected function getJoin(File $file): Builder
    {
        return $file
            ->join('users as users', 'files.creator_id', 'users.id')
            ->join('mime_types as mime_types', 'files.mime_type_id', 'mime_types.id')
            ->join('users as owner_users', 'files.owner_id', 'owner_users.id')
            ->join('users as user_users', 'files.user_id', 'user_users.id');
    }

    /**
     * @return array|string[]
     */
    protected function getColumn(): array
    {
        return [
            'files.id',
            'files.name',
            'files.description',
            'files.alt',
            'files.path',
            'files.mime_type_id',
            'files.creator_id',
            'files.owner_id',
            'files.user_id',
            'users.name AS creator_name',
            'mime_types.name AS mime_type_name',
            'owner_users.name AS owner_name',
            'user_users.name AS user_name'
        ];
    }

    /**
     * @inheritDoc
     */
    public function update(int $id, FileRepositoryRequest $fileRepositoryRequest, File $file): ?File
    {
        $file = $this->getById($id, $file);
        if ($file != null) {
            try {
                $file = Lazy::copy($fileRepositoryRequest, $file);
                $file->save();
                return $file;
            } catch (QueryException $queryException) {
                report($queryException);
            }
        }
        return $file;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id, File $file): ?File
    {
        return $this->getJoin($file)->find($id, $this->getColumn());
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id, File $file): bool
    {
        $file = $this->getById($id, $file);
        if ($file != null) {
            return $file->delete();
        } else {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function get(File $file, int $length = 12, string $q = null): LengthAwarePaginator
    {
        return $this->getJoin($file)
            ->when($q != null, function ($query) use ($q) {
                return $query->where('files.name', 'LIKE', '%' . $q . '%');
            })
            ->orderBy('files.name', 'asc')
            ->paginate($length, $this->getColumn());
    }

    /**
     * @inheritDoc
     */
    public function getCount(File $file, string $q = null): int
    {
        return $file
            ->when($q != null, function ($query) use ($q) {
                return $query->where('files.name', 'LIKE', '%' . $q . '%');
            })
            ->count();
    }

    /**
     * @inheritDoc
     */
    public function getByName(string $name, File $file): ?File
    {
        return $this->getJoin($file)
            ->where('files.name', $name)
            ->first($this->getColumn());
    }
}
