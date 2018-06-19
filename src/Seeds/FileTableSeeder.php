<?php

namespace WebAppId\Content\Seeds;

use Illuminate\Database\Seeder;
use WebAppId\Content\Models\File;

class FileTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user_id = '1';
        $objFile = new File;
        if($objFile->getFileCount()==0){
            $objNewFile = new \StdClass;

            $objNewFile->name = 'none';
            $objNewFile->description = '';
            $objNewFile->alt = '';
            $objNewFile->path = 'default.png';
            $objNewFile->mime_type_id = '32';
            $objNewFile->owner_id = $user_id;
            $objNewFile->user_id = $user_id;

            $objFile->addFile($objNewFile);
        }
    }
}
