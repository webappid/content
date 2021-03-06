<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-02-04
 * Time: 18:39
 */

namespace WebAppId\Content\Controllers;


use WebAppId\Content\Services\FileService;

/**
 * Class FileShowController
 * @package WebAppId\Content\Controllers
 */
class FileShowController
{
    /**
     * @param string $name
     * @param string $size
     * @param FileService $fileService
     * @return mixed
     */
    public function __invoke(string $name, string $size, FileService $fileService)
    {
        return app()->call([$fileService, 'get'], ['name' => $name, 'size' => $size]);
    }
}
