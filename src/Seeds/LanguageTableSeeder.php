<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Seeds;

use Illuminate\Container\Container;
use Illuminate\Database\Seeder;
use WebAppId\Content\Repositories\LanguageRepository;
use WebAppId\Content\Services\AddLanguageParam;

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
     * @param AddLanguageParam $addLanguageParam
     * @return void
     */
    public function run(LanguageRepository $languages, Container $container, AddLanguageParam $addLanguageParam)
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
    
            $addLanguageParam->setCode($key['code']);
            $addLanguageParam->setName($key['name']);
            $addLanguageParam->setImageId($key['image_id']);
            $addLanguageParam->setUserId(1);
            $addLanguageParam->setImageId(1);
    
            $resultLanguage = $container->call([$languages, 'getLanguageByName'], ['name' => $addLanguageParam->getName()]);
            
            if ($resultLanguage === null) {
                $container->call([$languages, 'addLanguage'], ['addLanguageParam' => $addLanguageParam]);
            }
        }
    }
}
