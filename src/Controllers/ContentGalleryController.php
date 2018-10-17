<?php

namespace WebAppId\Content\Controllers;

use WebAppId\Content\Controllers\Controller;
use WebAppId\Content\Controllers\FileController;
use WebAppId\Content\Models\ContentGallery AS ContentGalleryModel;
use WebAppId\Content\Models\TimeZone AS TimeZoneModel;
use WebAppId\Content\Requests\ContentGalleryRequest;

use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Carbon\Carbon;

abstract class ContentGalleryController extends Controller{
    protected abstract function storeResult($result);

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContentGalleryRequest $request, ContentGalleryModel $contentModel, TimeZoneModel $timeZoneModel)
    {
        return $this->storeResult();
    }
}