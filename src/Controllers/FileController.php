<?php

namespace WebAppId\Content\Controllers;

use WebAppId\Content\Requests\UploadRequest;

use WebAppId\Content\Presenters\FileService;

use Illuminate\Container\Container;

class FileController extends Controller{
    public function create($path, UploadRequest $upload, FileService $fileService, Container $container){
        return $container->call([$fileService, 'store'],['path'=>$path, 'upload' => $upload]);
    }
}