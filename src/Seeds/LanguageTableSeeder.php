<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Seeds;

use Illuminate\Container\Container;
use Illuminate\Database\Seeder;
use WebAppId\Content\Repositories\LanguageRepository;

/**
 * Class LanguageTableSeeder
 * @package WebAppId\Content\Seeds
 */
class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param LanguageRepository $languages
     * @param Container $container
     * @return void
     */
    public function run(LanguageRepository $languages, Container $container)
    {
        $image_id = 1;
        $data = [
            [
                'code' => 'ID',
                'name' => 'Indonesian',
                'image_id' => $image_id
            ],
            [
                'code' => 'EN',
                'name' => 'English',
                'image_id' => $image_id
            ]
        ];
        
        foreach ($data as $key) {
            $request = new \stdClass;
            $request->code = $key['code'];
            $request->name = $key['name'];
            $request->image_id = $key['image_id'];
            $request->user_id = '1';
            $request->image_id = 1;
            
            $resultLanguage = $container->call([$languages, 'getLanguageByName'], ['name' => $request->name]);
            
            if ($resultLanguage === null) {
                $container->call([$languages, 'addLanguage'], ['request' => $request]);
            }
        }
    }
}
