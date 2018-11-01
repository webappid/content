<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Seeds;

use Illuminate\Database\Seeder;

use WebAppId\Content\Repositories\ContentStatusRepository;

use Illuminate\Container\Container;

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
     * @param Container $container
     * @return void
     */
    public function run(ContentStatusRepository $contentStatus, Container $container)
    {
        $data = [
            [
                'name' => 'Draft',
            ],
            [
                'name' => 'Publish',
            ],
            [
                'name' => 'Unpublish',
            ],
            [
                'name' => 'Cancel',
            ],
            [
                'name' => 'Comming Soon',
            ],
        ];
        
        foreach ($data as $key) {
            $request = new \stdClass;
            $request->name = $key['name'];
            $request->user_id = '1';
            
            if (count($container->call([$contentStatus, 'getContentStatusesByName'], ['name' => $request->name])) == 0) {
                $container->call([$contentStatus, 'addContentStatus'], ['request' => $request]);
            }
        }
    }
}
