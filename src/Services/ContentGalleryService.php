<?php

namespace WebAppId\Content\Services;

use WebAppId\Content\Services\FileService;
use WebAppId\Content\Repositories\ContentGalleryRepository;
use WebAppId\Content\Repositories\TimeZoneRepository;
use WebAppId\Content\Requests\ContentGalleryRequest;

use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Carbon\Carbon;

class ContentGalleryService
{

    private $container;
    private $user_id;

    public function __construct(Container $container){
        $this->container = $container;
        $this->user_id = Auth::id()==null?session('user_id'):Auth::id();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($path, $contentGalleryRequest, $fileRequest, FileService $fileService, ContentGalleryRepository $contentGalleryRepository, TimeZoneRepository $timeZoneRepository)
    {
        $result = $this->container->call([$fileService, 'store'],['path'=>$path, 'upload' => $fileRequest]);
        
        $contentGalleryRequest->user_id = $this->user_id;
        $contentGalleryRequest->file_id = $result[0]->id;
        if($contentGalleryRequest->description == null){
            $contentGalleryRequest->description = '';
        }

        return $this->container->call([$contentGalleryRepository, 'addContentGallery'],['request' => $contentGalleryRequest]);
    }
}