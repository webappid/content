<?php

namespace WebAppId\Content\Controllers;

use WebAppId\Content\Controllers\ContentController;

use WebAppId\Content\Services\ContentGalleryService;

use WebAppId\Content\Requests\ContentGalleryRequest;

use WebAppId\Content\Requests\UploadRequest;

use Illuminate\Contracts\Container\Container;


class ContentGalleryTest
{
    function store($path, ContentGalleryRequest $contentGalleryRequest, Container $container, UploadRequest $upload, ContentGalleryService $contentGalleryService){
        return $container->call([$contentGalleryService,'store'],['path' => $path, 'contentGalleryRequest' => $contentGalleryRequest, 'fileRequest' => $upload]);
    }
}