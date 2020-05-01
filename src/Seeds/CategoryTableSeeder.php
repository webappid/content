<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Seeds;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Seeder;
use WebAppId\Content\Repositories\CategoryRepository;
use WebAppId\Content\Repositories\Requests\CategoryRepositoryRequest;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 22/04/20
 * Time: 05.40
 * Class CategoryTableSeeder
 * @package WebAppId\Content\Seeds
 */
class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param CategoryRepository $categoryRepository
     * @return void
     * @throws BindingResolutionException
     */
    public function run(CategoryRepository $categoryRepository)
    {

        $categories[] = array('name' => 'page', 'code' => 'page', 'status_id' => '1', 'user_id' => '1');

        foreach ($categories as $category) {
            $result = $this->container->call([$categoryRepository, 'getByCode'], ['code' => $category['code']]);
            if ($result === null) {
                $categoryRepositoryRequest = $this->container->make(CategoryRepositoryRequest::class);

                $categoryRepositoryRequest->name = $category['name'];
                $categoryRepositoryRequest->code = $category['code'];
                $categoryRepositoryRequest->status_id = $category['status_id'];
                $categoryRepositoryRequest->user_id = $category['user_id'];
                $this->container->call([$categoryRepository, 'store'], compact('categoryRepositoryRequest'));
            }
        }
    }
}
