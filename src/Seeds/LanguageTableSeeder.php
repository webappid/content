<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Seeds;

use Illuminate\Contracts\Container\BindingResolutionException as BindingResolutionExceptionAlias;
use Illuminate\Database\Seeder;
use WebAppId\Content\Repositories\LanguageRepository;
use WebAppId\Content\Repositories\Requests\LanguageRepositoryRequest;

/**
 * Class LanguageTableSeeder
 * @package WebAppId\Content\Seeds
 */
class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param LanguageRepository $languageRepository
     * @return void
     * @throws BindingResolutionExceptionAlias
     */
    public function run(LanguageRepository $languageRepository)
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

            $languageRepositoryRequest = app()->make(LanguageRepositoryRequest::class);

            $languageRepositoryRequest->code = $key['code'];
            $languageRepositoryRequest->name = $key['name'];
            $languageRepositoryRequest->image_id = $key['image_id'];
            $languageRepositoryRequest->user_id = 1;

            $resultLanguage = app()->call([$languageRepository, 'getByName'], ['name' => $key['name']]);

            if ($resultLanguage === null) {
                app()->call([$languageRepository, 'store'], compact('languageRepositoryRequest'));
            }
        }
    }
}
