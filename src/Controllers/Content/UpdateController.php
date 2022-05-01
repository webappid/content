<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-02-03
 * Time: 16:15
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
 * Time: 19.08
 * Class UpdateController
 * @package WebAppId\Content\Controllers\Admin\Content\Edit
 */
class UpdateController
{
    use Content;

    /**
     * @param string $slug
     * @param ContentRequest $contentUpdateRequest
     * @param ContentService $contentService
     * @param SmartResponse $smartResponse
     * @param ContentServiceRequest $contentServiceRequest
     * @param Response $response
     * @return \Illuminate\Http\Response|String
     */
    public function __invoke(string                $slug,
                             ContentRequest        $contentUpdateRequest,
                             ContentService        $contentService,
                             SmartResponse         $smartResponse,
                             ContentServiceRequest $contentServiceRequest,
                             Response              $response)
    {
        $request = $contentUpdateRequest->validated();

        $contentServiceRequest = $this->transformContent($request, $contentServiceRequest);

        $result = app()->call([$contentService, 'update'], ['code' => $slug, 'contentServiceRequest' => $contentServiceRequest]);
        if ($result->status) {
            $response->setMessage('Save Data Success');
            return $smartResponse->saveDataSuccess($response);
        } else {
            return $smartResponse->saveDataFailed($response);
        }
    }
}
