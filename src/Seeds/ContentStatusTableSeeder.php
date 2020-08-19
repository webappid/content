<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Seeds;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Seeder;
use WebAppId\Content\Repositories\ContentStatusRepository;
use WebAppId\Content\Repositories\Requests\ContentStatusRepositoryRequest;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 22/04/20
 * Time: 05.35
 * Class ContentStatusTableSeeder
 * @package WebAppId\Content\Seeds
 */
class ContentStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param ContentStatusRepository $contentStatusRepository
     * @return void
     * @throws BindingResolutionException
     */
    public function run(ContentStatusRepository $contentStatusRepository)
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

            $contentStatusRepositoryRequest = app()->make(ContentStatusRepositoryRequest::class);

            $contentStatusRepositoryRequest->name = $key['name'];
            $contentStatusRepositoryRequest->user_id = 1;

            if (app()->call([$contentStatusRepository, 'getByName'], ['name' => $contentStatusRepositoryRequest->name]) == null) {
                app()->call([$contentStatusRepository, 'store'], compact('contentStatusRepositoryRequest'));
            }
        }
    }
}
