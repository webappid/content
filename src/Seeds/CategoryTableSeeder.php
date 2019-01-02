<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Seeds;

use Illuminate\Database\Seeder;
use WebAppId\Content\Repositories\CategoryRepository;

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
     * @return void
     */
    public function run(CategoryRepository $category)
    {
        
        $categories[] = array('name' => 'page', 'code' => 'page', 'status_id' => '1', 'user_id' => '1');
        
        for ($i = 0; $i < count($categories); $i++) {
            $result = $this->container->call([$category, 'getCategoryByCode'], ['code' => $categories[$i]['code']]);
            
            if ($result === null) {
                $this->container->call([$category, 'addCategory'], ['data' => (Object)$categories[$i]]);
            }
        }
    }
}
