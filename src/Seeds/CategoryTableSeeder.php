<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Seeds;

use Illuminate\Database\Seeder;
use WebAppId\Content\Repositories\CategoryRepository;
use WebAppId\Content\Services\Params\AddCategoryParam;

/**
 * Class CategoryTableSeeder
 * @package WebAppId\Content\Seeds
 */
class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param CategoryRepository $category
     * @param AddCategoryParam $addCategoryParam
     * @return void
     */
    public function run(CategoryRepository $category, AddCategoryParam $addCategoryParam)
    {
        
        $categories[] = array('name' => 'page', 'code' => 'page', 'status_id' => '1', 'user_id' => '1');
        
        for ($i = 0; $i < count($categories); $i++) {
            $result = $this->container->call([$category, 'getCategoryByCode'], ['code' => $categories[$i]['code']]);
            
            if ($result === null) {
                $addCategoryParam->setName($categories[$i]['name']);
                $addCategoryParam->setCode($categories[$i]['code']);
                $addCategoryParam->setStatusId($categories[$i]['status_id']);
                $addCategoryParam->setUserId($categories[$i]['user_id']);
                $this->container->call([$category, 'addCategory'], ['addCategoryParam' => $addCategoryParam]);
            }
        }
    }
}
