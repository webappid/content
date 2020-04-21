<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 04/11/18
 * Time: 03.50
 */

namespace WebAppId\Content\Seeds;


use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Seeder;
use WebAppId\Content\Repositories\CategoryStatusRepository;
use WebAppId\Content\Repositories\Requests\CategoryStatusRepositoryRequest;

/**
 * Class CategoryStatusTableSeeder
 * @package WebAppId\Content\Seeds
 */
class CategoryStatusTableSeeder extends Seeder
{
    /**
     * @param CategoryStatusRepository $categoryStatusRepository
     * @throws BindingResolutionException
     */
    public function run(CategoryStatusRepository $categoryStatusRepository)
    {
        $categoryStatuses = array(
            'active',
            'not active'
        );

        foreach ($categoryStatuses as $categoryStatus) {
            $result = $this->container->call([$categoryStatusRepository, 'getByName'], ['name' => $categoryStatus]);
            if ($result == null) {
                $categoryStatusRepositoryRequest = $this->container->make(CategoryStatusRepositoryRequest::class);
                $categoryStatusRepositoryRequest->name = $categoryStatus;

                $this->container->call([$categoryStatusRepository, 'store'], compact('categoryStatusRepositoryRequest'));
            }
        }
    }
}
