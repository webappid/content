<?php

namespace WebAppId\Content\Controllers\Content;

use WebAppId\Content\Services\ContentService;
use WebAppId\SmartResponse\Response;
use WebAppId\SmartResponse\SmartResponse;

class DetailController
{
    public function __invoke(string         $slug,
                             ContentService $contentService,
                             SmartResponse  $smartResponse,
                             Response       $response)
    {

        $result = app()->call([$contentService, 'detail'], ['code' => $slug]);

        $data = $result->content;
        $data['galleries'] = $result->galleries;
        if ($result->status) {
            $response->setData($data);
            return $smartResponse->success($response);
        } else {
            return $smartResponse->dataNotFound($response);
        }
    }
}
