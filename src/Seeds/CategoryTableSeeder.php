<?php

namespace WebAppId\Content\Seeds;

use WebAppId\Content\Repositories\CategoryRepository;
use Illuminate\Database\Seeder;

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

        $categories[] = array('name' => 'page', 'code' => 'page', 'user_id' => '1');

        for ($i = 0; $i < count($categories); $i++) {
            $result = $category->getCategoryByCode($categories[$i]['code']);

            if ($result === null) {
                $category->addCategory((Object)$categories[$i]);
            }
        }
    }
}
