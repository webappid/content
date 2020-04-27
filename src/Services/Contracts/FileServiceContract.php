<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */
namespace WebAppId\Content\Services\Contracts;


use WebAppId\Content\Repositories\FileRepository;
use WebAppId\Content\Repositories\MimeTypeRepository;
use WebAppId\Content\Repositories\Requests\FileRepositoryRequest;
use WebAppId\Content\Repositories\Requests\MimeTypeRepositoryRequest;
use WebAppId\Content\Tools\SmartReadFile;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 25/04/20
 * Time: 14.31
 * Interface FileServiceContract
 * @package WebAppId\Content\Services\Contracts
 */
interface FileServiceContract
{
    /**
     * @param string $name
     * @param FileRepository $file
     * @param SmartReadFile $smartReadFile
     * @param bool $download
     * @return mixed
     */
    public function index(string $name,
                          FileRepository $file,
                          SmartReadFile $smartReadFile,
                          bool $download = false);

    /**
     * @param string $path
     * @param $upload
     * @param MimeTypeRepositoryRequest $mimeTypeRepositoryRequest
     * @param MimeTypeRepository $mimeTypeRepository
     * @param FileRepositoryRequest $fileRepositoryRequest
     * @param FileRepository $fileRepository
     * @return mixed
     */
    public function store(string $path,
                          $upload,
                          MimeTypeRepositoryRequest $mimeTypeRepositoryRequest,
                          MimeTypeRepository $mimeTypeRepository,
                          FileRepositoryRequest $fileRepositoryRequest,
                          FileRepository $fileRepository);

    /**
     * @param string $path
     * @param $upload
     * @param MimeTypeRepositoryRequest $mimeTypeRepositoryRequest
     * @param MimeTypeRepository $mimeTypeRepository
     * @param FileRepositoryRequest $fileRepositoryRequest
     * @param FileRepository $fileRepository
     * @return mixed
     */
    public function storeMulti(string $path,
                               $upload,
                               MimeTypeRepositoryRequest $mimeTypeRepositoryRequest,
                               MimeTypeRepository $mimeTypeRepository,
                               FileRepositoryRequest $fileRepositoryRequest,
                               FileRepository $fileRepository);

    /**
     * @param $name
     * @param $size
     * @param FileRepository $file
     * @param SmartReadFile $smartReadFile
     * @return mixed
     */
    public function get($name, $size,
                        FileRepository $file,
                        SmartReadFile $smartReadFile);
}
