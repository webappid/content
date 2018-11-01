<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Controllers;

use WebAppId\Content\Requests\UploadRequest;
use WebAppId\Content\Services\FileService;
use Illuminate\Container\Container;

/**
 * Class FileController
 * @package WebAppId\Content\Controllers
 */
class FileController extends Controller
{
    /**
     * @param $path
     * @param UploadRequest $upload
     * @param FileService $fileService
     * @param Container $container
     * @return mixed
     */
    public function create($path, UploadRequest $upload, FileService $fileService, Container $container)
    {
        return $container->call([$fileService, 'store'], ['path' => $path, 'upload' => $upload]);
    }
}