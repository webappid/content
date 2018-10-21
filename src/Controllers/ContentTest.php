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
    function update($result)
    {
        return $result;
    }
    function destroy($result)
    {
        if($result){
            return "Delete Success";
        }else{
            return "Delete Failed";
        }
    }

    function detail($result){
        return $result;
    }

    public function store(Container $container, ContentPresenter $contentPresenter, ContentRequest $contentRequest){
        return $container->call([$contentPresenter,'store'],['request'=>$contentRequest]);
    }
}
