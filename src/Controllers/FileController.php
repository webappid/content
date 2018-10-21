<?php

namespace WebAppId\Content\Controllers;

use WebAppId\Content\Requests\UploadRequest;

use WebAppId\Content\Presenters\FilePresenter;

use Illuminate\Container\Container;

class FileController extends Controller{
    public function create($path, UploadRequest $upload, FilePresenter $filePresenter, Container $container){
        return $container->call([$filePresenter, 'store'],['path'=>$path, 'upload' => $upload]);
    }
}