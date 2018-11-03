<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 04/11/18
 * Time: 03.50
 */

namespace WebAppId\Content\Seeds;


use Illuminate\Database\Seeder;
use WebAppId\Content\Repositories\CategoryStatusRepository;

/**
 * Class CategoryStatusTableSeeder
 * @package WebAppId\Content\Seeds
 */
class CategoryStatusTableSeeder extends Seeder
{
    /**
     * @param CategoryStatusRepository $categoryStatusRepository
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
                $objCategoryStatus = new \StdClass;
                $objCategoryStatus->name = $categoryStatus;
                
                $this->container->call([$categoryStatusRepository, 'addCategoryStatus'],['request' => $objCategoryStatus]);
            }
        }
    }
}