<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Services;

use WebAppId\Content\Repositories\ContentGalleryRepository;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Facades\Auth;

/**
 * Class ContentGalleryService
 * @package WebAppId\Content\Services
 */

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
     * @param $path
     * @param $contentGalleryRequest
     * @param $fileRequest
     * @param \WebAppId\Content\Services\FileService $fileService
     * @param ContentGalleryRepository $contentGalleryRepository
     * @return \Illuminate\Http\Response
     */
    public function store($path, $contentGalleryRequest, $fileRequest, FileService $fileService, ContentGalleryRepository $contentGalleryRepository)
    {
        $result = $this->container->call([$fileService, 'store'],['path'=>$path, 'upload' => $fileRequest]);
        
        $contentGalleryRequest->user_id = $this->user_id;
        $contentGalleryRequest->file_id = $result[0]->id;
        if(!isset($contentGalleryRequest->description)){
            $contentGalleryRequest->description = '';
        }

        return $this->container->call([$contentGalleryRepository, 'addContentGallery'],['request' => $contentGalleryRequest]);
    }
}