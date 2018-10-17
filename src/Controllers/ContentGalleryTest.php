<?php

namespace WebAppId\Content\Controllers;

use WebAppId\Content\Controllers\ContentController;

class ContentGalleryTest extends ContentGalleryController
{
    protected function storeResult($container){
        $container->call('WebAppId\Content\Controllers\FileController@create');
        dd('test');
    }
}