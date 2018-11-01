<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Controllers;

use Illuminate\Container\Container;
use WebAppId\Content\Requests\ContentRequest;
use WebAppId\Content\Services\ContentService;

/**
 * Class ContentTest
 * @package WebAppId\Content\Controllers
 */
class ContentTest extends Controller
{
    function index()
    {
    
    }
    
    /**
     * @param Container $container
     * @param ContentService $contentService
     * @return mixed
     */
    function show(Container $container, ContentService $contentService)
    {
        return $container->call([$contentService, 'show']);
    }
    
    function create()
    {
    
    }
    
    /**
     * @param $code
     * @param Container $container
     * @param ContentService $contentService
     * @return mixed
     */
    function edit($code, Container $container, ContentService $contentService)
    {
        return $container->call([$contentService, 'edit'], ['code' => $code]);
    }
    
    /**
     * @param $code
     * @param ContentService $contentService
     * @param ContentRequest $contentRequest
     * @param Container $container
     * @return mixed
     */
    function update($code, ContentService $contentService, ContentRequest $contentRequest, Container $container)
    {
        return $container->call([$contentService, 'edit'], ['code' => $code, 'request' => $contentRequest]);
    }
    
    /**
     * @param $code
     * @param Container $container
     * @param ContentService $contentService
     * @return string
     */
    function destroy($code, Container $container, ContentService $contentService)
    {
        $result = $container->call([$contentService, 'destroy'], ['code' => $code]);
        if ($result) {
            return "Delete Success";
        } else {
            return "Delete Failed";
        }
    }
    
    /**
     * @param $code
     * @param Container $container
     * @param ContentService $contentService
     * @return mixed
     */
    function detail($code, Container $container, ContentService $contentService)
    {
        return $container->call([$contentService, 'detail'], ['code' => $code]);
    }
    
    /**
     * @param Container $container
     * @param ContentService $contentService
     * @param ContentRequest $contentRequest
     * @return mixed
     */
    public function store(Container $container, ContentService $contentService, ContentRequest $contentRequest)
    {
        return $container->call([$contentService, 'store'], ['request' => $contentRequest]);
    }
}
