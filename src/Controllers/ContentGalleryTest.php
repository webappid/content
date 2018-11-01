<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Controllers;

use WebAppId\Content\Services\ContentGalleryService;
use WebAppId\Content\Requests\ContentGalleryRequest;
use WebAppId\Content\Requests\UploadRequest;
use Illuminate\Contracts\Container\Container;

/**
 * Class ContentGalleryTest
 * @package WebAppId\Content\Controllers
 */
class ContentGalleryTest
{
    function store($path, ContentGalleryRequest $contentGalleryRequest, Container $container, UploadRequest $upload, ContentGalleryService $contentGalleryService)
    {
        return $container->call([$contentGalleryService, 'store'], ['path' => $path, 'contentGalleryRequest' => $contentGalleryRequest, 'fileRequest' => $upload]);
    }
}