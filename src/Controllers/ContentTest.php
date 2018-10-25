<?php

namespace WebAppId\Content\Controllers;

use Illuminate\Container\Container;

use WebAppId\Content\Controllers\ContentController;

use WebAppId\Content\Requests\ContentRequest;

use WebAppId\Content\Presenters\ContentPresenter;

class ContentTest extends Controller
{
    function index()
    {

    }
    function show(Container $container, ContentPresenter $contentPresenter)
    {
        return $container->call([$contentPresenter,'show']);
    }
    function create()
    {
        
    }

    function edit($code, Container $container, ContentPresenter $contentPresenter)
    {
        return $container->call([$contentPresenter,'edit'],['code' => $code]);
    }
    function update($code, ContentPresenter $contentPresenter, ContentRequest $contentRequest, Container $container)
    {   
        return $container->call([$contentPresenter,'edit'],['code' => $code, 'request'=>$contentRequest]);
    }
    function destroy($code, Container $container, ContentPresenter $contentPresenter)
    {
        $result = $container->call([$contentPresenter,'destroy'],['code' => $code]);
        if($result){
            return "Delete Success";
        }else{
            return "Delete Failed";
        }
    }

    function detail($code, Container $container, ContentPresenter $contentPresenter){
        return $container->call([$contentPresenter,'detail'],['code' => $code]);
    }

    public function store(Container $container, ContentPresenter $contentPresenter, ContentRequest $contentRequest){
        return $container->call([$contentPresenter,'store'],['request'=>$contentRequest]);
    }
}
