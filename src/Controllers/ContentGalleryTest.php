<?php

namespace WebAppId\Content\Controllers;

use WebAppId\Content\Controllers\ContentController;

use WebAppId\Content\Requests\ContentGalleryRequest;

use Illuminate\Container\Container;

class ContentGalleryTest
{
    function store($path, ContentGalleryRequest $contentGalleryRequest, Container $container, FileController $fileController){
        $result = $container->call([$fileController,'create'],['path'=>$path]);
        
    }
}