<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Seeds;

use Illuminate\Container\Container;
use Illuminate\Database\Seeder;
use WebAppId\Content\Repositories\FileRepository;
use WebAppId\Content\Repositories\Requests\FileRepositoryRequest;

/**
 * Class FileTableSeeder
 * @package WebAppId\Content\Seeds
 */
class FileTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param FileRepository $file
     * @param FileRepositoryRequest $fileRepositoryRequest
     * @param Container $container
     * @return void
     */
    public function run(FileRepository $file, FileRepositoryRequest $fileRepositoryRequest, Container $container)
    {
        //
        $user_id = '1';
        if ($container->call([$file, 'getCount']) == 0) {

            $fileRepositoryRequest->name = 'default.png';
            $fileRepositoryRequest->description = '';
            $fileRepositoryRequest->alt = '';
            $fileRepositoryRequest->path = 'default';
            $fileRepositoryRequest->mime_type_id = '32';
            $fileRepositoryRequest->creator_id = $user_id;
            $fileRepositoryRequest->owner_id = $user_id;
            $fileRepositoryRequest->user_id = $user_id;

            $container->call([$file, 'store'], compact('fileRepositoryRequest'));
        }
    }
}
