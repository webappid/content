<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-02-05
 * Time: 06:00
 */

namespace WebAppId\Content\Controllers\Content;

use WebAppId\Content\Requests\SearchRequest;
use WebAppId\Content\Services\ContentService;
use WebAppId\Content\Services\Requests\ContentServiceSearchRequest;
use WebAppId\SmartResponse\Response;
use WebAppId\SmartResponse\SmartResponse;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 07/09/19
 * Time: 19.08
 * Class ListController
 * @package WebAppId\Content\Controllers\Admin\Content\Show
 */
class ListController
{
    public function __invoke(SearchRequest               $searchRequest,
                             ContentService              $contentService,
                             ContentServiceSearchRequest $contentServiceSearchRequest,
                             SmartResponse               $smartResponse,
                             Response                    $response)
    {
        $searchValue = $searchRequest->validated();

        if (!empty($searchValue)) {
            if (isset($searchValue['q'])) {
                $contentServiceSearchRequest->q = $searchValue['q'];
            } else {
                $contentServiceSearchRequest->q = $searchValue['search']['value'] != null ? $searchValue['search']['value'] : '';
            }
        }

        $contentServiceSearchRequest->categories = ["page", "content"];
        $resultSearch = app()->call([$contentService, 'get'], compact('contentServiceSearchRequest'));

        if ($resultSearch->status) {
            $response->setRecordsTotal($resultSearch->count);
            $response->setData($resultSearch->contentList);
            $response->setRecordsFiltered($resultSearch->countFiltered);
            return $smartResponse->success($response);
        } else {
            $response->setRecordsFiltered(0);
            $response->setRecordsTotal(0);
            return $smartResponse->dataNotFound($response);
        }
    }
}
