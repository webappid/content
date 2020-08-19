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
            $result = app()->call([$categoryStatusRepository, 'getByName'], ['name' => $categoryStatus]);
            if ($result == null) {
                $categoryStatusRepositoryRequest = app()->make(CategoryStatusRepositoryRequest::class);
                $categoryStatusRepositoryRequest->name = $categoryStatus;

                app()->call([$categoryStatusRepository, 'store'], compact('categoryStatusRepositoryRequest'));
            }
        }
    }
}
