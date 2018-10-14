<?php

namespace WebAppId\Content\Controllers;

use WebAppId\Content\Controllers\ContentController;

class ContentTest extends ContentController
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

    protected function detail($result){
        return $result;
    }
}
