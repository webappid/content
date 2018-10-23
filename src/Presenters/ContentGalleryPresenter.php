<?php

namespace WebAppId\Content\Controllers;

use WebAppId\Content\Presenters\FilePresenter;
use WebAppId\Content\Repositories\ContentGalleryRepository;
use WebAppId\Content\Repositories\TimeZoneRepository;
use WebAppId\Content\Requests\ContentGalleryRequest;

use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Carbon\Carbon;

abstract class ContentGalleryPresenter
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($contentRequest, $fileRequest, ContentGalleryRepository $contentRepository, TimeZoneRepository $timeZoneRepository)
    {
        return null;
    }
}