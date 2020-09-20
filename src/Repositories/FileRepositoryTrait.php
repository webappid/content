<?php
/**
 * Created by PhpStorm.
 */

namespace WebAppId\Content\Repositories;


use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Content\Models\File;
use WebAppId\Content\Repositories\Requests\FileRepositoryRequest;
use WebAppId\DDD\Tools\Lazy;
use WebAppId\Lazy\Traits\RepositoryTrait;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 20/09/2020
 * Time: 22.08
 * Class FileRepositoryTrait
 * @package WebAppId\Content\Repositories
 */
trait FileRepositoryTrait
{
    use RepositoryTrait;

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
     * @inheritDoc
     */
    public function update(int $id, FileRepositoryRequest $fileRepositoryRequest, File $file): ?File
    {
        $file = $file->find($id);
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
        return $this
            ->getJoin($file)
            ->find($id, $this->getColumn());
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id, File $file): bool
    {
        $file = $file->find($id);
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