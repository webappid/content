<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Seeds;

use Illuminate\Database\Seeder;

/**
 * Class DatabaseSeeder
 * @package WebAppId\Content\Seeds
 */

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(TimeZoneTableSeeder::class);
        $this->call(MimeTypeTableSeeder::class);
        $this->call(FileTableSeeder::class);
        $this->call(LanguageTableSeeder::class);
        $this->call(ContentStatusTableSeeder::class);
        $this->call(CategoryTableSeeder::class);
    }
}