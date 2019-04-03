<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Seeds;

use Illuminate\Container\Container;
use Illuminate\Database\Seeder;
use WebAppId\Content\Repositories\FileRepository;
use WebAppId\Content\Services\Params\AddFileParam;

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
     * @param AddFileParam $addFileParam
     * @param Container $container
     * @return void
     */
    public function run(FileRepository $file, AddFileParam $addFileParam, Container $container)
    {
        //
        $user_id = '1';
        if ($container->call([$file, 'getFileCount']) == 0) {
    
            $addFileParam->setName('default.png');
            $addFileParam->setDescription('');
            $addFileParam->setAlt('');
            $addFileParam->setPath('default');
            $addFileParam->setMimeTypeId('32');
            $addFileParam->setOwnerId($user_id);
            $addFileParam->setUserId($user_id);
    
            $container->call([$file, 'addFile'], ['addFileParam' => $addFileParam]);
        }
    }
}
