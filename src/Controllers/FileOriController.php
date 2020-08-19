<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-02-04
 * Time: 18:49
 */

namespace WebAppId\Content\Controllers;


use WebAppId\Content\Services\FileService;

/**
 * Class FileOriController
 * @package WebAppId\Content\Controllers
 */
class FileOriController
{
    /**
     * @param string $name
     * @param FileService $fileService
     * @return mixed
     */
    public function __invoke(string $name,
                             FileService $fileService)
    {
        return app()->call([$fileService, 'index'], ['name' => $name]);
    }
}
