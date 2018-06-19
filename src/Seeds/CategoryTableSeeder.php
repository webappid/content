<?php

namespace WebAppId\Content\Seeds;

use WebAppId\Content\Models\Category;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $objCategory = new Category;
        $objNewCategory = new \StdClass;

        $categories[] = array('name'=>'page', 'code'=>'page', 'user_id'=>'1');

        for ($i=0; $i < count($categories) ; $i++) {
            $result = $objCategory->getCategoryByCode($categories[$i]['code']);
            if($result!=null){ 
                $objCategory->addCategory((Object)$categories[$i]);
            }
        }
    }
}
