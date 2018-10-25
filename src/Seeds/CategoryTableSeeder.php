<?php

namespace WebAppId\Content\Seeds;

use WebAppId\Content\Repositories\CategoryRepository;
use Illuminate\Database\Seeder;

use Illuminate\Container\Container;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(CategoryRepository $category)
    {
        //
        $objNewCategory = new \StdClass;

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
