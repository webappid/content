<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-02-04
 * Time: 18:49
 */

namespace WebAppId\Content\Controllers;


use WebAppId\Content\Services\FileService;
use WebAppId\DDD\Controllers\BaseController;

/**
 * Class FileOriController
 * @package WebAppId\Content\Controllers
 */
class FileOriController extends BaseController
{
    /**
     * @param string $name
     * @param FileService $fileService
     * @return mixed
     */
    public function __invoke(string $name,
                             FileService $fileService)
    {
        return $this->container->call([$fileService, 'index'], ['name' => $name]);
    }
}
