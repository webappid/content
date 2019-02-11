<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Seeds;

use Illuminate\Container\Container;
use Illuminate\Database\Seeder;
use WebAppId\Content\Repositories\ContentStatusRepository;
use WebAppId\Content\Services\Params\AddContentStatusParam;

/**
 * Class ContentStatusTableSeeder
 * @package WebAppId\Content\Seeds
 */
class ContentStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param ContentStatusRepository $contentStatus
     * @param AddContentStatusParam $addContentStatusParam
     * @param Container $container
     * @return void
     */
    public function run(ContentStatusRepository $contentStatus,
                        AddContentStatusParam $addContentStatusParam,
                        Container $container)
    {
        $data = [
            [
                'name' => 'Draft',
            ],
            [
                'name' => 'Publish',
            ],
            [
                'name' => 'Un-publish',
            ],
            [
                'name' => 'Cancel',
            ],
            [
                'name' => 'Coming Soon',
            ],
        ];
        
        foreach ($data as $key) {
    
            $addContentStatusParam->setName($key['name']);
            $addContentStatusParam->setUserId(1);
    
            if (count($container->call([$contentStatus, 'getContentStatusesByName'], ['name' => $addContentStatusParam->getName()])) == 0) {
                $container->call([$contentStatus, 'addContentStatus'], ['addContentStatusParam' => $addContentStatusParam]);
            }
        }
    }
}
