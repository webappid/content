<?php

namespace WebAppId\Content\Controllers;

use Illuminate\Container\Container;

use WebAppId\Content\Controllers\ContentController;

use WebAppId\Content\Requests\ContentRequest;

use WebAppId\Content\Presenters\ContentService;

class ContentTest extends Controller
{
    function index()
    {

    }
    function show(Container $container, ContentService $contentService)
    {
        return $container->call([$contentService,'show']);
    }
    function create()
    {
        
    }

    function edit($code, Container $container, ContentService $contentService)
    {
        return $container->call([$contentService,'edit'],['code' => $code]);
    }
    function update($code, ContentService $contentService, ContentRequest $contentRequest, Container $container)
    {   
        return $container->call([$contentService,'edit'],['code' => $code, 'request'=>$contentRequest]);
    }
    function destroy($code, Container $container, ContentService $contentService)
    {
        $result = $container->call([$contentService,'destroy'],['code' => $code]);
        if($result){
            return "Delete Success";
        }else{
            return "Delete Failed";
        }
    }

    function detail($code, Container $container, ContentService $contentService){
        return $container->call([$contentService,'detail'],['code' => $code]);
    }

    public function store(Container $container, ContentService $contentService, ContentRequest $contentRequest){
        return $container->call([$contentService,'store'],['request'=>$contentRequest]);
    }
}
