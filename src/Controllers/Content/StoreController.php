<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-02-03
 * Time: 16:02
 */

namespace WebAppId\Content\Controllers\Content;

use WebAppId\Content\Requests\ContentRequest;
use WebAppId\Content\Services\ContentService;
use WebAppId\Content\Services\Requests\ContentServiceRequest;
use WebAppId\Content\Traits\Content;
use WebAppId\SmartResponse\Response;
use WebAppId\SmartResponse\SmartResponse;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 07/09/19
 * Time: 19.07
 * Class StoreController
 * @package WebAppId\Content\Controllers\Admin\Content\Add
 */
class StoreController
{
    use Content;

    /**
     * @param ContentRequest $contentRequest
     * @param ContentService $contentService
     * @param ContentServiceRequest $contentServiceRequest
     * @param SmartResponse $smartResponse
     * @param Response $response
     * @return \Illuminate\Http\Response|String
     */
    public function __invoke(ContentRequest        $contentRequest,
                             ContentService        $contentService,
                             ContentServiceRequest $contentServiceRequest,
                             SmartResponse         $smartResponse,
                             Response              $response): \Illuminate\Http\Response|string
    {
        $request = $contentRequest->validated();

        $contentServiceRequest = $this->transformContent($request, $contentServiceRequest);

        $result = app()->call([$contentService, 'store'], compact('contentServiceRequest'));

        if ($result->status) {
            $response->setMessage('Create new content data success');
            return $smartResponse->saveDataSuccess($response);
        } else {
            return $smartResponse->saveDataFailed($response);
        }
    }
}
