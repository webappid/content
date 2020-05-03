<?php

/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Content\Repositories;

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

    protected function getColumn($file)
    {
        return $file
            ->select
            (
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
            )
            ->join('users as users', 'files.creator_id', 'users.id')
            ->join('mime_types as mime_types', 'files.mime_type_id', 'mime_types.id')
            ->join('users as owner_users', 'files.owner_id', 'owner_users.id')
            ->join('users as user_users', 'files.user_id', 'user_users.id');
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
        return $this->getColumn($file)->find($id);
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
        return $this->getColumn($file)
            ->when($q != null, function ($query) use ($q) {
                return $query->where('files.name', 'LIKE', '%' . $q . '%');
            })
            ->orderBy('files.name', 'asc')
            ->paginate($length);
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
        return $this->getColumn($file)
            ->where('files.name', $name)
            ->first();
    }

    /**
     * @param AddFileParam $addFileParam
     * @param File $file
     * @return File|null
     * @deprecated
     */
    public function addFile(AddFileParam $addFileParam, File $file): ?File
    {
        try {
            $file = Lazy::copy($addFileParam, $file);

            $file->save();

            return $file;
        } catch (QueryException $e) {
            report($e);
            return null;
        }
    }

    /**
     * @param int $id
     * @param File $file
     * @return File|null
     * @deprecated
     */
    public function getOne(int $id, File $file): ?File
    {
        return $file->findOrFail($id);
    }


    /**
     * @param File $file
     * @return int
     */
    public function getFileCount(File $file): int
    {
        return $file->count();
    }
}
