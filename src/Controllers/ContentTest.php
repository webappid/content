<?php

namespace WebAppId\Content\Controllers;

use WebAppId\Content\Controllers\ContentController;

use Illuminate\Container\Container;

class ContentTest
{
    protected function indexResult()
    {

    }
    protected function showResult($result)
    {
        return $result;
    }
    protected function createResult()
    {
        
    }
    protected function storeResult($result)
    {
        return $result;
    }
    protected function editResult($result)
    {
        return $result;
    }
    protected function updateResult($result)
    {
        return $result;
    }
    protected function destroyResult($result)
    {
        if($result){
            return "Delete Success";
        }else{
            return "Delete Failed";
        }
    }

    protected function detailResult($result){
        return $result;
    }

    public function presenter(Container $container){
        $result =  $container->call('WebAppId\Content\Presenters\ContentPresenter@store');
        return $result;
    }
}
