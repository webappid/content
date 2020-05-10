<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-02-04
 * Time: 18:35
 */

namespace WebAppId\Content\Controllers;


use Illuminate\Support\Facades\Auth;
use WebAppId\Content\Requests\UploadRequest;
use WebAppId\Content\Services\FileService;
use WebAppId\DDD\Controllers\BaseController;
use WebAppId\SmartResponse\Response;
use WebAppId\SmartResponse\SmartResponse;

/**
 * Class FileStoreController
 * @package App\Http\Tools
 */
class FileStoreController extends BaseController
{
    /**
     * @param UploadRequest $uploadRequest
     * @param FileService $fileService
     * @param SmartResponse $smartResponse
     * @param Response $response
     * @return \Illuminate\Http\Response|String
     */
    public function __invoke(UploadRequest $uploadRequest,
                             FileService $fileService,
                             SmartResponse $smartResponse,
                             Response $response)
    {
        if (!Auth::check()) {
            session(['user_id' => 1]);
        }
        $result = $this->container->call([$fileService, 'store'], ['path' => 'images', 'upload' => $uploadRequest]);

        $responseData = [];
        $responseData['image_url'] = route('file.ori', $result[0]['name']);
        $responseData['id'] = $result[0]['id'];

        if ($result != null && count($result) == 1) {
            $response->setData($responseData);
            return $smartResponse->saveDataSuccess($response);
        } else {
            return $smartResponse->saveDataFailed(null);
        }
    }
}
