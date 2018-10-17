<?php

namespace WebAppId\Content\Seeds;

use Illuminate\Database\Seeder;
use \WebAppId\Content\Repositories\LanguageRepository;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(LanguageRepository $languages)
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
            $request           = new \stdClass;
            $request->code     = $key['code'];
            $request->name     = $key['name'];
            $request->image_id = $key['image_id'];
            $request->user_id  = '1';
            $request->image_id = 1;

            $resultLanguage = $languages->getLanguageByName($request->name);

            if ($resultLanguage === null) {
                $languages->addLanguage($request);
            }
        }
    }
}
