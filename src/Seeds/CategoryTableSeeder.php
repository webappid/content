<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Seeds;

use WebAppId\Content\Repositories\CategoryRepository;
use Illuminate\Database\Seeder;

use Illuminate\Container\Container;

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
        //

        $container = new Container;

        $categories[] = array('name' => 'page', 'code' => 'page', 'user_id' => '1');

        for ($i = 0; $i < count($categories); $i++) {
            $result = $container->call([$category,'getCategoryByCode'],['code' => $categories[$i]['code']]);

            if ($result === null) {
                $container->call([$category,'addCategory'],['data' => (Object)$categories[$i]]);
            }
        }
    }
}
